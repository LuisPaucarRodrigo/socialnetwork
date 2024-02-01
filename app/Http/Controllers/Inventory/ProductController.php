<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\OutputProjectProduct;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Warehouse;
use App\Models\WarehousesHeader;
use App\Models\ProjectProduct;
use App\Models\Header;
use App\Models\Product;
use App\Models\ProductsHeader;

class ProductController extends Controller
{
    //Warehouses
    public function index(Warehouse $warehouse)
    {
        $products = Product::where('warehouse_id', $warehouse->id)->with('productHeaders')->get();
        return Inertia::render('Inventory/WarehouseManagement/Product', [
            'products' => $products,
            'warehouse' => $warehouse
        ]);
    }

    public function store(Warehouse $warehouse, Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'warehouse_id' => $warehouse->id,
        ]);

        foreach ($request->contentIds as $headerId => $content) {
            ProductsHeader::create([
                'product_id' => $product->id,
                'header_id' => $headerId,
                'content' => $content,
            ]);
        }
    }

    public function create(Warehouse $warehouse)
    {
        $headers = $warehouse->headers;
        //dd($headers);
        return Inertia::render('Inventory/WarehouseManagement/ProductForm', [
            'headers' => $headers,
            'warehouse' => $warehouse
        ]);
    }

    public function destroy(Warehouse $warehouse, Product $product) {
        $product->delete();
        return to_route('warehouses.products', ['warehouse' => $warehouse->id]);
    }

    public function outputs_index($warehouse) {
            $project_products = ProjectProduct::whereHas('product', function ($query) use ($warehouse) {
                $query->where('warehouse_id', $warehouse);
            })
            ->with('product')
            ->paginate(10);
        return Inertia::render('Inventory/WarehouseManagement/Outputs', [
            'project_products' => $project_products
        ]);
    }
    public function outputs_store(Request $request) {
        $data = $request->validate([
            'project_product_id' => 'required',
            'quantity' => 'required',
            'observation' => 'required',
        ]);
        OutputProjectProduct::create($data);
        return redirect()->back();
    }

}
