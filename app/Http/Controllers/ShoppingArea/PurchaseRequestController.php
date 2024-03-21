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
        return Inertia::render('ShoppingArea/PurchaseRequest/Purchases', [
            'purchases' => Purchasing_request::with('project','preproject','purchase_quotes')
                ->withCount([
                    'purchase_quotes',
                    'purchase_quotes as purchase_quotes_with_state_count' => function ($query) {
                        $query->where('state',true);
                    },
                    'purchase_quotes as purchase_quotes_without_state_count' => function ($query) {
                        $query->where('state',false);
                    }
                ])
                ->orderBy('created_at', 'desc')->paginate(),
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
        $purchases = Purchasing_request::with('project', 'products')->find($id->id);
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
        $data = $request->validated();
        if ($request->hasFile('purchase_doc')) {
            $croppedImage = $request->file('purchase_doc');
            $imageName = 'purchase_doc' . time() . '.' . $croppedImage->getClientOriginalExtension();
            $croppedImage->move(public_path('documents/quote/'), $imageName);
            $data['purchase_doc'] = $imageName;
        }
        $purchaseQuote = Purchase_quote::create($data);
        $cleanedProducts = array_map(function ($product) {
            return array_intersect_key($product, array_flip(['quantity', 'unitary_amount']));
        }, $data['products']);
        $purchaseQuote->products()->sync($cleanedProducts);
        return redirect()->back();
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
        $currentDate = now();
        $purchasesLessThanThreeDays = Purchasing_request::where('due_date', '<=', $currentDate->copy()->addDays(3))
            ->get();
        $filteredPurchasesLessThanThreeDays = $purchasesLessThanThreeDays->filter(function ($purchase) {
            return $purchase->state !== 'Completada';
        });
        $totalPurchasesLessThanThreeDays = $filteredPurchasesLessThanThreeDays->count();
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
    
    public function search($request)
    {
        $searchTerm = strtolower($request); // Convertir a minúsculas

        $purchasing_requests_by_title = Purchasing_request::where(function($query) use ($searchTerm) {
            $query->whereRaw('LOWER(title) like ?', ['%'.$searchTerm.'%'])
                  ->withCount([
                        'purchase_quotes',
                        'purchase_quotes as purchase_quotes_with_state_count' => function ($query) {
                            $query->where('state',true);
                        },
                        'purchase_quotes as purchase_quotes_without_state_count' => function ($query) {
                            $query->where('state',false);
                        }
                    ])
                   ->with('project','preproject','purchase_quotes')
                   ->orderBy('created_at', 'desc');
        })->get();

        $queryByCode = Purchasing_request::where(function($query) use ($searchTerm) {
            $query->withCount([
                        'purchase_quotes',
                        'purchase_quotes as purchase_quotes_with_state_count' => function ($query) {
                            $query->where('state',true);
                        },
                        'purchase_quotes as purchase_quotes_without_state_count' => function ($query) {
                            $query->where('state',false);
                        }
                    ])
                   ->with('project','preproject','purchase_quotes')
                   ->orderBy('created_at', 'desc');
        });

        $purchasing_requests_by_code = $queryByCode->get()->filter(function ($purchasing_request) use ($searchTerm) {
            return str_contains(strtolower($purchasing_request->code), $searchTerm);
        });

        $combined_purchasing_requests = $purchasing_requests_by_title->merge($purchasing_requests_by_code)->unique();

        return Inertia::render('ShoppingArea/PurchaseRequest/Purchases', [
            'purchases' => $combined_purchasing_requests,
            'search' => $request
        ]);
    }
}
