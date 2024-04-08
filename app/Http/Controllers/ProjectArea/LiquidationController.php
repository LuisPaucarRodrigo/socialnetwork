<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Liquidation;
use App\Models\Project;
use App\Models\ProjectProduct;
use App\Models\Warehouse;
use App\Models\ProjectEntry;
use App\Models\Refund;
use App\Models\ProjectEntryLiquidation;
use App\Models\RetrievalEntry;

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
        $project_entries = ProjectEntry::where('project_id', $project_id)
            ->with([
                'project',
                'entry.inventory.purchase_product',
                'special_inventory.purchase_product',
                'project_entry_outputs',
            ])
            ->whereDoesntHave('project_entry_liquidation')
            ->paginate(10);

        $project_entries->getCollection()->transform(function ($project_entry) {
            return $project_entry->outputs_state ? $project_entry : null;
        })->filter();
    
        $liquidations = ProjectEntryLiquidation::whereHas('project_entry.project', function ($query) use ($project_id) {
            $query->where('project_id', $project_id);
        })->with('project_entry.entry.inventory.purchase_product', 'project_entry.special_inventory.purchase_product')->paginate(5);
    
        return Inertia::render('ProjectArea/ProjectManagement/Liquidations', [
            'liquidations' => $liquidations,
            'project_entries' => $project_entries,
            'project_id' => $project_id
        ]);
    }

    public function liquidateForm($project_id, ProjectEntry $project_entry)
    {
        return Inertia::render('ProjectArea/ProjectManagement/LiquidationForm', [
            'project_entry' => $project_entry,
            'project_id' => $project_id
        ]);
    }

    public function liquidate($project_id, $project_entry, Request $request)
    {
        $request->validate([
            'liquidated_quantity' => 'required|numeric',
            'refund_quantity' => 'required|numeric',
        ]);

        $project_entry_liquidation = ProjectEntryLiquidation::create([
            'project_entry_id' => $project_entry,
            'liquidated_quantity' => $request->liquidated_quantity,
            'refund_quantity' => $request->refund_quantity,
            'observations' => $request->observations
        ]);

        if($request->devolution_value == 1){

            $project_entry_object = ProjectEntry::with('special_inventory', 'entry.inventory')->find($project_entry);
            $warehouse_id = null;

            if ($project_entry_object->special_inventory){
                $warehouse_id = $project_entry_object->special_inventory->warehouse_id;
            }else{
                $warehouse_id = $project_entry_object->entry->inventory->warehouse_id;
            }

            Refund::create([
                'project_entry_liquidation_id' => $project_entry_liquidation->id,
                'quantity' => $request->refund_quantity,
                'warehouse_id' => $warehouse_id
            ]);

        }else if ($request->devolution_value == 0){

            $project_entry_id = ProjectEntry::find($project_entry);
            RetrievalEntry::create([
                'project_entry_liquidation_id' => $project_entry_liquidation->id,
                'entry_id' => $project_entry_id->entry_id
            ]);
        }
    }
    
}
