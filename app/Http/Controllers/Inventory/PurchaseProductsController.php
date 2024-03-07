<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Purchase_product;
use App\Providers\GlobalFunctionsServiceProvider;

class PurchaseProductsController extends Controller
{
    //Warehouses
    public function index()
    {
        $hasAllPermissions = GlobalFunctionsServiceProvider::hasAllPermissions();
        $products = Purchase_product::with('purchasing_request_product', 'purchase_quote_product')
                       ->where('state', true)
                       ->paginate(10);            
        return Inertia::render('Inventory/PurchaseProducts/Products', [
            'products' => $products,
            'admin' => $hasAllPermissions
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'unit' => 'required',
            'description' => 'required',
        ]);
        
        $product = Purchase_product::create([
            'name' => $request->name,
            'unit' => $request->unit,
            'description' => $request->description,
        ]);

    }

    public function update(Request $request, Purchase_product $purchase_product)
    {
        $request->validate([
            'name' => 'required|string',
            'unit' => 'required',
            'description' => 'required',
        ]);

        $purchase_product->update([
            'name' => $request->name,
            'unit' => $request->unit,
            'description' => $request->description
        ]);
    }

    public function disable(Purchase_product $purchase_product)
    {
        $purchase_product->update([
            'state' => false
        ]);
        return to_route('inventory.purchaseproducts');
    }
}
