<?php

namespace App\Http\Controllers\ShoppingArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseQuoteRequest\CreatePurchaseQuoteRequest;
use App\Http\Requests\PurchaseRequest\CreatePurchaseRequest;
use App\Http\Requests\PurchaseRequest\UpdatePurchaseRequest;
use App\Models\Project;
use App\Models\Purchase_product;
use App\Models\Purchasing_requests_product;
use App\Models\Purchasing_request;
use App\Models\Provider;
use App\Models\Purchase_order;
use App\Models\Purchase_quote;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Providers\GlobalFunctionsServiceProvider;

class PurchaseRequestController extends Controller
{
    public function index()
    {
        $hasAllPermissions = GlobalFunctionsServiceProvider::hasAllPermissions();
        return Inertia::render('ShoppingArea/PurchaseRequest/Purchases', [
            'purchases' => Purchasing_request::with('project')->orderBy('created_at', 'desc')->paginate(),
            'admin' => $hasAllPermissions
        ]);
    }

    public function create($project_id= null)
    {
        return Inertia::render('ShoppingArea/PurchaseRequest/CreateAndUpdateRequest', [
            'allProducts'=>Purchase_product::all(),
            'project' => Project::find($project_id),
        ]);
    }

    public function store(CreatePurchaseRequest $request)
    {
        $validateData = $request->validated();
        $prToCreate = Purchasing_request::create($validateData);
        $pr_products = $request->products;
        foreach($pr_products as $item) {
            Purchasing_requests_product::create([
                'purchase_product_id'=> $item['id'],
                'purchasing_request_id'=> $prToCreate->id,
                'quantity' => $item['quantity'],
            ]);
        }
    }

    public function edit($id, $project_id=null)
    {
        $purchase = Purchasing_request::with('products')->find($id);
        return Inertia::render('ShoppingArea/PurchaseRequest/CreateAndUpdateRequest', [
            'purchase' => $purchase,
            'allProducts'=>Purchase_product::all(),
            'project' => Project::find($project_id),
        ]);
    }
    public function update(UpdatePurchaseRequest $request, $id)
    {   
        $validateData = $request->validated();
        $purchases = Purchasing_request::with('project')->findOrFail($id);
        $purchases->update($validateData);

        return redirect()->back();
    }

    public function index_quotes(Purchasing_request $id)
    {
        $purchases = null;
        if ($id->project_id) {
            $purchases = Purchasing_request::with('project')->find($id->id);
        } else {
            $purchases = Purchasing_request::find($id->id);
        }
        return Inertia::render('ShoppingArea/PurchaseRequest/RequestQuotes', [
            'providers' => Provider::all(),
            'purchases' => $purchases
        ]);
    }

    public function destroy($id)
    {
        Purchasing_request::destroy($id);
        return redirect()->back();
    }

    public function details($id)
    {
        return Inertia::render('ShoppingArea/PurchaseRequest/PurchasingDetails', ['details' => Purchasing_request::with('project','products')->find($id)]);
    }

    public function quote(CreatePurchaseQuoteRequest $request)
    {
        $imageName =  null;
        if ($request->hasFile('purchase_doc')) {
            $croppedImage = $request->file('purchase_doc');
            $imageName = 'purchase_doc' . time() . '.' . $croppedImage->getClientOriginalExtension();
            $croppedImage->move(public_path('documents/quote/'), $imageName);
        }

        Purchase_quote::create([
            'provider' => $request->provider,
            'amount' => $request->amount,
            'quote_deadline' => $request->quote_deadline,
            'response' => $request->response,
            'purchase_doc' => $imageName,
            'purchasing_request_id' => $request->purchasing_request_id,
        ]);

        return to_route('purchasesrequest.index');
    }

    public function showDocument(Purchase_quote $id)
    {
        $fileName = $id->purchase_doc;
        $filePath = public_path("documents/quote/$fileName");
        if (file_exists($filePath)) {
            return response()->file($filePath);
        }
        abort(404, 'Documento no encontrado');
    }

    public function reject_request(Purchasing_request $id)
    {
        $id->update(['is_accepted' => false]);
    }

    public function doTask()
    {
        // Obtener la fecha actual ajustada por el desfase
        $currentDate = now();

        // Obtener todos los Purchasing_request dentro del rango de 0 a 3 días
        $purchasesLessThanThreeDays = Purchasing_request::where('due_date', '<=', $currentDate->copy()->addDays(3))
            ->get();

        $filteredPurchasesLessThanThreeDays = $purchasesLessThanThreeDays->filter(function ($purchase) {
            return $purchase->state !== 'Completada';
        });

        $totalPurchasesLessThanThreeDays = $filteredPurchasesLessThanThreeDays->count();

        // Obtener todos los Purchasing_request dentro del rango de 4 a 7 días
        $purchasesBetweenFourAndSevenDays = Purchasing_request::where('due_date', '>=', $currentDate->copy()->addDays(4))
            ->where('due_date', '<=', $currentDate->copy()->addDays(7))
            ->get();

        $filteredPurchasesBetweenFourAndSevenDays = $purchasesBetweenFourAndSevenDays->filter(function ($purchase) {
            return $purchase->state !== 'Completada';
        });

        $totalPurchasesBetweenFourAndSevenDays = $filteredPurchasesBetweenFourAndSevenDays->count();

        return response()->json([
            'purchasesLessThanThreeDays' => $filteredPurchasesLessThanThreeDays,
            'totalPurchasesLessThanThreeDays' => $totalPurchasesLessThanThreeDays,
            'purchasesBetweenFourAndSevenDays' => $filteredPurchasesBetweenFourAndSevenDays,
            'totalPurchasesBetweenFourAndSevenDays' => $totalPurchasesBetweenFourAndSevenDays,
        ]);
    }


    //purchase request product
    public function purchasing_request_product_store (Request $request) {
        $data = $request->validate([
            'purchase_product_id'=> 'required',
            'purchasing_request_id'=> 'required',
            'quantity'=> 'required',
        ]);
        $newProduct = Purchasing_requests_product::create($data);
        return response()->json([
            'newProductId' => $newProduct->id,
        ], 200);
    }

    public function purchasing_request_product_delete ($purchasing_request_product_id) {
        Purchasing_requests_product::find($purchasing_request_product_id)->delete();
        return redirect()->back();
    }

}
