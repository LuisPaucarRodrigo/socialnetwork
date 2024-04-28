<?php

namespace App\Http\Controllers\ShoppingArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseQuoteRequest\CreatePurchaseQuoteRequest;
use App\Http\Requests\PurchaseRequest\CreatePurchaseRequest;
use App\Http\Requests\PurchaseRequest\UpdatePurchaseRequest;
use App\Models\NormalEntry;
use App\Models\Project;
use App\Models\Purchase_product;
use App\Models\Purchasing_requests_product;
use App\Models\Purchasing_request;
use App\Models\Provider;
use App\Models\Purchase_quote;
use App\Models\ResourceType;
use App\Models\RetrievalEntry;
use App\Models\SpecialInventory;
use App\Models\TypeProduct;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PurchaseRequestController extends Controller
{
    public function index()
    {
        return Inertia::render('ShoppingArea/PurchaseRequest/Purchases', [
            'purchases' => Purchasing_request::with('project', 'preproject', 'purchase_quotes')
                ->withCount([
                    'purchase_quotes',
                    'purchase_quotes as purchase_quotes_with_state_count' => function ($query) {
                        $query->where('state', true);
                    },
                    'purchase_quotes as purchase_quotes_without_state_count' => function ($query) {
                        $query->where('state', false);
                    }
                ])
                ->orderBy('created_at', 'desc')->paginate(),
        ]);
    }

    public function create()
    {
        return Inertia::render('ShoppingArea/PurchaseRequest/CreateAndUpdateRequest', [
            'allProducts' => Purchase_product::with('resource_type')->get(),
            'typeProduct' => TypeProduct::all(),
            'resourceType' => ResourceType::all()
        ]);
    }

    public function store(CreatePurchaseRequest $request)
    {
        $validateData = $request->validated();
        $prToCreate = Purchasing_request::create($validateData);
        $pr_products = $request->products;
        foreach ($pr_products as $item) {
            Purchasing_requests_product::create([
                'purchase_product_id' => $item['id'],
                'purchasing_request_id' => $prToCreate->id,
                'quantity' => $item['quantity'],
            ]);
        }
    }

    public function edit($id)
    {   
        $purchase = Purchasing_request::with('products')->find($id);
        $type = $purchase->products->first()->type;
        
        return Inertia::render('ShoppingArea/PurchaseRequest/CreateAndUpdateRequest', [
            'purchase' => $purchase,
            'typeProduct' => TypeProduct::all(),
            'allProducts' => Purchase_product::with('resource_type')->where('type',$type)->get(),
            'resourceType' => ResourceType::all()
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
        $purchases = Purchasing_request::with('project', 'purchasing_request_product.purchase_product')->find($id->id);
        return Inertia::render('ShoppingArea/PurchaseRequest/RequestQuotes', [
            'providers' => Provider::all(),
            'purchases' => $purchases
        ]);
    }

    public function purchase_quote_deadline_complete($id)
    {
        return Inertia::render('ProjectArea/PreProject/Expense', [
            'quotes' => Purchase_quote::with('provider', 'purchasing_requests')
                ->whereHas('purchasing_requests', function ($query) use ($id) {
                    $query->where('project_id', $id);
                })
                ->where('quote_deadline', null)
                ->paginate(),
            'purchases' => true
        ]);
    }

    public function destroy($id)
    {
        Purchasing_request::destroy($id);
        return redirect()->back();
    }

    public function details($id)
    {
        return Inertia::render('ShoppingArea/PurchaseRequest/PurchasingDetails', ['details' => Purchasing_request::with('project', 'products')->find($id)]);
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
        $purchasesBetweenFourAndSevenDays = Purchasing_request::where('due_date', '>=', $currentDate->copy()->addDays(4))
            ->where('due_date', '<=', $currentDate->copy()->addDays(7))
            ->get();
        $filteredPurchasesBetweenFourAndSevenDays = $purchasesBetweenFourAndSevenDays->filter(function ($purchase) {
            return $purchase->state !== 'Completada';
        });
        return response()->json([
            'purchasesLessThanThreeDays' => $filteredPurchasesLessThanThreeDays,
            'purchasesBetweenFourAndSevenDays' => $filteredPurchasesBetweenFourAndSevenDays,
        ]);
    }


    //purchase request product
    public function purchasing_request_product_store(Request $request)
    {
        $data = $request->validate([
            'purchase_product_id' => 'required',
            'purchasing_request_id' => 'required',
            'quantity' => 'required',
        ]);
        $newProduct = Purchasing_requests_product::create($data);
        return response()->json([
            'newProductId' => $newProduct->id,
        ], 200);
    }

    public function purchasing_request_product_delete($purchasing_request_product_id)
    {
        Purchasing_requests_product::find($purchasing_request_product_id)->delete();
        return redirect()->back();
    }

    public function search($request)
    {
        $searchTerm = strtolower($request);

        $purchasing_requests_by_title = Purchasing_request::with('project', 'preproject', 'purchase_quotes')->where(function ($query) use ($searchTerm) {
            $query->whereRaw('LOWER(title) like ?', ['%' . $searchTerm . '%'])
                ->withCount([
                    'purchase_quotes',
                    'purchase_quotes as purchase_quotes_with_state_count' => function ($query) {
                        $query->where('state', true);
                    },
                    'purchase_quotes as purchase_quotes_without_state_count' => function ($query) {
                        $query->where('state', false);
                    }
                ])
                ->with('project', 'preproject', 'purchase_quotes')
                ->orderBy('created_at', 'desc');
        })->get();

        $queryByCode = Purchasing_request::with('project', 'preproject', 'purchase_quotes')->where(function ($query) use ($searchTerm) {
            $query->withCount([
                'purchase_quotes',
                'purchase_quotes as purchase_quotes_with_state_count' => function ($query) {
                    $query->where('state', true);
                },
                'purchase_quotes as purchase_quotes_without_state_count' => function ($query) {
                    $query->where('state', false);
                }
            ])
                ->with('project', 'preproject', 'purchase_quotes')
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

    public function purchase_quote_complete_details(Purchase_quote $id)
    {
        $expense = $id->load('purchasing_requests.preproject', 'purchase_order', 'provider', 'products');
        return Inertia::render('ProjectArea/PreProject/Details', ['expense' => $expense, 'quotes' => true]);
    }

    public function export(Purchasing_request $id)
    {
        $user = Auth::user();
        $purchasing_request = $id->load('purchasing_request_product');
        $pdf = Pdf::loadView('pdf.PurchasingRequestPDF', compact('purchasing_request', 'user'));
        return $pdf->stream();
    }

    public function existing_products(Request $request)
    {
        $ids = $request->all(); // Aquí obtienes [2, 3] como una matriz de IDs

        // Inicializar el arreglo para almacenar los resultados
        $results = [];

        // Iterar sobre cada ID proporcionado
        foreach ($ids as $id) {
            $result = new \stdClass;

            $result->cantidadRetrieval = RetrievalEntry::whereHas('entry.inventory', function ($query) use ($id) {
                $query->where('purchase_product_id', $id);
            })->where('purchase_product_id', $id)->value('quantity');

            $result->cantidadNormal = NormalEntry::whereHas('entry.inventory', function ($query) use ($id) {
                $query->where('purchase_product_id', $id);
            })->where('purchase_product_id', $id)->value('quantity');

            $result->cantidadSpecial = SpecialInventory::where('purchase_product_id', $id)->value('quantity');

            $results[] = $result;
        }

        return $results;
    }
}
