<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Purchase_product;
use Illuminate\Validation\Rule;

class PurchaseProductsController extends Controller
{
    public function index()
    {
        $products = Purchase_product::with('purchasing_request_product', 'purchase_quote_product')
            ->where('state', true)
            ->paginate(10);
        return Inertia::render('Inventory/PurchaseProducts/Products', [
            'products' => $products,
        ]);
    }

    public function search($request)
    {
        $searchTerm = strtolower($request); // Convertir a minúsculas

        $queryByName = Purchase_product::with('purchasing_request_product', 'purchase_quote_product')
            ->where('state', true)
            ->whereRaw('LOWER(name) like ?', ['%' . $searchTerm . '%']);

        $queryByCode = Purchase_product::with('purchasing_request_product', 'purchase_quote_product')
            ->where('state', true);

        $productsByName = $queryByName->get();

        $productsByCode = $queryByCode->get()->filter(function ($product) use ($searchTerm) {
            return str_contains(strtolower($product->code), $searchTerm);
        });

        // Combinar los resultados de ambos filtros y crear una instancia de LengthAwarePaginator
        $combinedProducts = $productsByName->merge($productsByCode)->unique();

        // $perPage = 10;
        // $currentPage = LengthAwarePaginator::resolveCurrentPage();
        // $currentPageProducts = $combinedProducts->slice(($currentPage - 1) * $perPage, $perPage);
        // $products = new LengthAwarePaginator(
        //     $currentPageProducts,
        //     $combinedProducts->count(),
        //     $perPage,
        //     $currentPage,
        //     ['path' => url()->current(), 'query' => []]
        // );

        return Inertia::render('Inventory/PurchaseProducts/Products', [
            'products' => $combinedProducts,
            'search' => $request
        ]);
    }


    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|string|unique:purchase_products',
            'unit' => 'required',
            'type' => 'required',
            'description' => 'nullable|string'
        ]);

        Purchase_product::create($validateData);
    }

    public function update(Request $request, Purchase_product $purchase_product)
    {
        $validateData =  $request->validate([
            'name' => ['required','string',Rule::unique('purchase_products')->ignore($purchase_product)],
            'unit' => 'required',
            'type' => 'required',
            'description' => 'nullable|string'
        ]);

        $purchase_product->update($validateData);
    }

    public function disable(Purchase_product $purchase_product)
    {
        $purchase_product->update([
            'state' => false
        ]);
        return to_route('inventory.purchaseproducts');
    }
}
