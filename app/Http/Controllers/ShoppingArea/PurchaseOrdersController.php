<?php

namespace App\Http\Controllers\ShoppingArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseOrderRequest\CreatePurchaseOrderRequest;
use App\Models\Purchase_order;
use App\Models\Warehouse;
use Inertia\Inertia;
use App\Models\Entry;
use App\Models\Inventory;
use App\Models\PurchasesEntry;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseOrdersController extends Controller
{
    public function index()
    {
        return Inertia::render('ShoppingArea/PurchaseOrders/Orders', [
            'orders' => Purchase_order::with('purchase_quote.purchasing_requests','purchase_quote.provider')
                ->where('state', '!=', 'Completada')
                ->where('state', '!=', 'Completada/Aprobada')
                ->paginate()
        ]);
    }


    public function history()
    {
        $completedOrders = Purchase_order::with('purchase_quote.purchasing_requests','purchase_quote.payment')
            ->where(function ($query) {
                $query->where('state', 'Completada')
                    ->orWhere('state', 'Completada/Aprobada');
            })
            ->paginate(10);


        return Inertia::render('ShoppingArea/PurchaseOrders/HistoryOrders', ['orders' => $completedOrders]);
    }


    public function state(CreatePurchaseOrderRequest $request)
    {
        $documentNameFacture = null;
        $documentNameRemission = null;
        if ($request->hasFile('facture_doc')) {
            $document = $request->file('facture_doc');
            $documentNameFacture = time() . '._' . $document->getClientOriginalName();
            $document->move(public_path('documents/purchaseorder/facture/'), $documentNameFacture);
        }
        if ($request->hasFile('remission_guide_doc')) {
            $document = $request->file('remission_guide_doc');
            $documentNameRemission = time() . '._' . $document->getClientOriginalName();
            $document->move(public_path('documents/purchaseorder/remission/'), $documentNameRemission);
        }
        
        $purchase_order = Purchase_order::find($request->id);
 
        $purchase_order->update([
            'state' => $request->state,
            'serie_number' => $request->serie_number,
            'facture_doc' => $documentNameFacture,
            'facture_date' => $request->facture_date,
            'facture_number' => $request->facture_number,
            'others' => $request->others,
            'remission_guide_doc' => $documentNameRemission,
            'remission_guide_date' => $request->remission_guide_date,
            'remission_guide_number' => $request->remission_guide_number,
        ]);
    }

    public function purchase_orders_alarms()
    {
        $today = Carbon::now();
        $purchaseOrders3d = Purchase_order::with('purchase_quote.purchasing_requests')
            ->where('state', '!=', 'Completada')
            ->where('state', '!=', 'Completada/Aprobada')
            ->whereHas('purchase_quote.purchasing_requests', function ($query) use ($today) {
                $query->where('due_date', '<=', $today->copy()->addDays(3));
            })
            ->get();
        $purchaseOrders7d = Purchase_order::with('purchase_quote.purchasing_requests')
            ->where('state', '!=', 'Completada')
            ->where('state', '!=', 'Completada/Aprobada')
            ->whereHas('purchase_quote.purchasing_requests', function ($query) use ($today) {
                $query->where('due_date', '>=', $today->copy()->addDays(3))
                    ->where('due_date', '<=', $today->copy()->addDays(7));
            })
            ->get();
        return response()->json([
            'purchaseOrders3d' => $purchaseOrders3d,
            'purchaseOrders7d' => $purchaseOrders7d,
        ]);
    }

    public function purchase_order_view($purchase_order_id)
    {
        return Inertia::render('ShoppingArea/PurchaseOrders/OrderDetails', [
            'purchase_order' => Purchase_order::with('purchase_quote.provider','purchase_quote.purchasing_requests.project.preproject')->find($purchase_order_id)
        ]);
    }

    public function purchase_orders_export(Purchase_order $id)
    {
        $purchase_order = $id->load('purchase_quote.provider','purchase_quote.purchasing_requests', 'purchase_quote.purchase_quote_products.purchase_product');
        $pdf = Pdf::loadView('pdf.PurchaseOrderPDF', compact('purchase_order'));
        return $pdf->stream();
    }

    public function showFacture(Purchase_order $id)
    {
        $fileName = $id->facture_doc;
        $filePath = public_path("documents/purchaseorder/facture/$fileName");
        if (file_exists($filePath)) {
            return response()->file($filePath);
        }
        abort(404, 'Documento no encontrado');
    }

    public function search($request, $history)
    {
        $searchTerm = strtolower($request); // Convertir a minúsculas
        
        $query = Purchase_order::with('purchase_quote.purchasing_requests', 'purchase_quote.payment');

        if ($history == 'history') {
            $query->where(function ($query) {
                $query->where('state', 'Completada')
                    ->orWhere('state', 'Completada/Aprobada');
            });
        } else {
            $query->whereNotIn('state', ['Completada', 'Completada/Aprobada']);
        }
    

        $purchase_orders_by_code = $query->get()->filter(function ($purchase_order) use ($searchTerm) {
            return str_contains(strtolower($purchase_order->code), $searchTerm);
        });

        $purchase_orders_request_title = $query->get()->filter(function ($purchase_order) use ($searchTerm) {
            return str_contains(strtolower($purchase_order->purchase_quote->purchasing_requests->title), $searchTerm);
        });

        $purchase_orders_request_code = $query->get()->filter(function ($purchase_order) use ($searchTerm) {
            return str_contains(strtolower($purchase_order->purchase_quote->purchasing_requests->code), $searchTerm);
        });

        $combined_purchase_orders = $purchase_orders_by_code->merge($purchase_orders_request_title)->merge($purchase_orders_request_code)->unique();

        if($history == 'history'){
            return Inertia::render('ShoppingArea/PurchaseOrders/HistoryOrders', [
                'orders' => $combined_purchase_orders,
                'search' => $request
            ]);
        }else{
            return Inertia::render('ShoppingArea/PurchaseOrders/Orders', [
                'orders' => $combined_purchase_orders,
                'search' => $request
            ]);
        }

        
    }

}
