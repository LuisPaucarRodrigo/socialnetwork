<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\OutputProjectProduct;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Warehouse;
use App\Models\ProjectProduct;

class ProductController extends Controller
{
    public function create(Warehouse $warehouse)
    {
        $headers = $warehouse->headers;
        return Inertia::render('Inventory/WarehouseManagement/ProductForm', [
            'headers' => $headers,
            'warehouse' => $warehouse,
            'products' => $warehouse->products,
        ]);
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
            'observation' => 'nullable|string',
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
