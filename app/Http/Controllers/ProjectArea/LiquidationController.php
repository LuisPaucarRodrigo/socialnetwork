<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Liquidation;
use App\Models\Project;

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

}
