<?php

namespace App\Http\Controllers\ShoppingArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseOrderRequest\CreatePurchaseOrderRequest;
use App\Models\Purchase_order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Log;

class PurchaseOrdersController extends Controller
{
    public function index()
    {
        return Inertia::render('ShoppingArea/PurchaseOrders/Orders', ['orders' => Purchase_order::with('purchase_quote.purchasing_requests.project')->paginate()]);
    }

    public function history()
    {
        $completedOrders = Purchase_order::with('purchase_quote.purchasing_requests.project')
            ->where('state', 'Completada')
            ->paginate();

        return Inertia::render('ShoppingArea/PurchaseOrders/HistoryOrders', ['orders' => $completedOrders]);
    }


    public function state(CreatePurchaseOrderRequest $request, $id)
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
        $purchase_order = Purchase_order::find($id);
        $purchase_order -> update([
            'state' => $request->state,
            'facture_doc' => $documentNameFacture,
            'facture_date' => $request->facture_date,
            'facture_number' => $request->facture_number,
            'remission_guide_doc'=> $documentNameRemission,
            'remission_guide_date' => $request->remission_guide_date,
            'remission_guide_number' => $request->remission_guide_number,
        ]);
    }

    // public function purchase_orders_alarms()
    // {
    //     $today = Carbon::now();
    //     $purchaseOrders3d = Purchase_order::with('purchase_quote.purchasing_requests')
    //         ->where('state', '!=', 'Completada')
    //         ->where('date_issue', '<=', $today->copy()->addDays(3))
    //         ->get();
    //     $purchaseOrders7d = Purchase_order::with('purchase_quote.purchasing_requests')
    //         ->where('state', '!=', 'Completada')
    //         ->where('date_issue', '>=', $today->copy()->addDays(3))
    //         ->where('date_issue', '<=', $today->copy()->addDays(7))
    // }
    public function purchase_orders_alarms(){
        $today = Carbon::now();
        $purchaseOrders3d = Purchase_order::with('purchase_quote.purchasing_requests')
            ->where('state', '!=', 'Completada')
            ->whereHas('purchase_quote.purchasing_requests', function ($query) use ($today) {
                $query->where('due_date', '<=', $today->copy()->addDays(3));
            })
            ->get();
        $purchaseOrders7d = Purchase_order::with('purchase_quote.purchasing_requests')
            ->where('state', '!=', 'Completada')
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
            'purchase_order' => Purchase_order::with('purchase_quote.purchasing_requests.project.preproject')->find($purchase_order_id)
        ]);
    }

    public function purchase_orders_export($id)
    {
        $dompdf = new Dompdf();
        $html = file_get_contents('ruta/a/tu/plantilla.html');
        $dompdf->loadHtml($html);
        $dompdf->render();
        $pdfContent = $dompdf->output();
    }
}
