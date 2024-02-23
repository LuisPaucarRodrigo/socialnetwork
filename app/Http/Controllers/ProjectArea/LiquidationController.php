<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Liquidation;
use App\Models\Project;
use App\Models\ProjectProduct;
use App\Models\Warehouse;

class LiquidationController extends Controller
{
    public function store(Project $project, Request $request)
    {
        $request->validate([
            'liquidated_quantity' => 'required|numeric',
            'refund_quantity' => 'required|numeric',
            'observations' => 'required|string'
        ]);

        $liquidation = Liquidation::create([
            'output_project_product_id' => $request->output_project_product_id,
            'liquidated_quantity' => $request->liquidated_quantity,
            'refund_quantity' => $request->refund_quantity,
            'observations' => $request->observations
        ]);  
        
        return redirect()->back();
    }

    public function index($project_id)
    {
        $assigned_products = ProjectProduct::where('project_id', $project_id)
            ->with([
                'product',
                'output_project_product' => function ($query) {
                    $query->whereDoesntHave('liquidation');
                },
            ])

            ->paginate(10);

        $warehouses = Warehouse::all();

        $liquidations = Liquidation::whereHas('output_project_product.project_product', function ($query) use ($project_id) {
            $query->where('project_id', $project_id);
        })->with('output_project_product.project_product')->paginate(5);

        return Inertia::render('ProjectArea/ProjectManagement/Liquidations', [
            'liquidations' => $liquidations,
            'assigned_products' => $assigned_products,
            'warehouses' => $warehouses,
            'project_id' => $project_id
        ]);
    }
}
