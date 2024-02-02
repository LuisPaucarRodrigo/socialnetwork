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

    public function edit(Warehouse $warehouse, Product $product)
    {
        $headers = $warehouse->headers;
        $product->load(['productHeaders', 'productHeaders.header']);
        $product_headers = $product->productHeaders;
        return Inertia::render('Inventory/WarehouseManagement/ProductFormEdit', [
            'product' => $product,
            'warehouse' => $warehouse,
            'headers' => $product_headers->load('header'),
        ]);
    }

    public function show(Warehouse $warehouse, Product $product)
    {
        $headers = $warehouse->headers;
        $product->load(['productHeaders', 'productHeaders.header']);
        $product_headers = $product->productHeaders;
        return Inertia::render('Inventory/WarehouseManagement/ProductInformation', [
            'product' => $product,
            'warehouse' => $warehouse,
            'headers' => $product_headers->load('header'),
        ]);
    }

    public function update(Warehouse $warehouse, Product $product, Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $product->update([
            'name' => $request->name,
        ]);

        // Eliminar los productHeaders existentes
        $product->productHeaders()->delete();

        // Crear las nuevas productHeaders proporcionadas en la solicitud
        foreach ($request->contentIds as $headerId => $content) {
            // Verificar si el header existe
            $headerExists = Header::where('id', $headerId)->exists();

            if ($headerExists) {
                ProductsHeader::create([
                    'product_id' => $product->id,
                    'header_id' => $headerId,
                    'content' => $content,
                ]);
            } else {
                // Manejar el caso donde el header no existe
            }
        }
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
            'project_products' => $project_products,
            'warehouse' => $warehouse
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

    public function outputs_history_index($warehouse) {
        $output_products = OutputProjectProduct::whereHas('project_product.product', function ($query) use ($warehouse) {
            $query->where('warehouse_id', $warehouse);
        })
        ->with(['project_product.product', 'project_product.project'])
        ->paginate(10);
        return Inertia::render('Inventory/WarehouseManagement/OutputsHistory', [
            'output_products' => $output_products,
            'warehouse' => $warehouse
        ]);
    }
    public function output_delete(OutputProjectProduct $output) {
        $output->delete();
        return redirect()->back();
    }




}
