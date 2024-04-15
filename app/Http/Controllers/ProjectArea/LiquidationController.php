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
use App\Models\Entry;
use App\Models\ProjectEntryLiquidation;
use App\Models\RetrievalEntry;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

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
        // Obtener todos los registros de ProjectEntry relacionados con el proyecto
        $project_entries = ProjectEntry::where('project_id', $project_id)
            ->with([
                'project',
                'entry.inventory.purchase_product',
                'special_inventory.purchase_product',
                'project_entry_outputs',
            ])
            ->whereDoesntHave('project_entry_liquidation')
            ->get();
    
        // Filtrar los registros por el atributo calculado outputs_state
        $filtered_entries = $project_entries->filter(function ($project_entry) {
            return $project_entry->outputs_state;
        });
    
        // Paginar los resultados filtrados
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPageEntries = $filtered_entries->slice(($currentPage - 1) * $perPage, $perPage);
    
        // Crear un paginador con los resultados filtrados
        $final_project_entries = new LengthAwarePaginator(
            $currentPageEntries,
            $filtered_entries->count(),
            $perPage,
            $currentPage,
            ['path' => url()->current(), 'query' => []]
        );
    
        // Renderizar la vista con los resultados paginados
        return Inertia::render('ProjectArea/ProjectManagement/Liquidations', [
            'project_entries' => $final_project_entries,
            'project_id' => $project_id
        ]);
    }
    


    public function history($project_id){
        $liquidations = ProjectEntryLiquidation::whereHas('project_entry.project', function ($query) use ($project_id) {
            $query->where('project_id', $project_id);
        })->with('project_entry.entry.inventory.purchase_product', 'project_entry.special_inventory.purchase_product')->paginate(5);
    
        return Inertia::render('ProjectArea/ProjectManagement/LiquidationsHistory', [
            'liquidations' => $liquidations,
            'project_id' => $project_id
        ]);
    }

    public function liquidateForm($project_id, ProjectEntry $project_entry)
    {
        return Inertia::render('ProjectArea/ProjectManagement/LiquidationForm', [
            'project_entry' => $project_entry->load('special_inventory', 'entry'),
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

            $today = Carbon::now();
            $project_entry_liquidation->load('project_entry.entry.inventory', 'project_entry.special_inventory');
            $purchase_product_id = null;

            if ($project_entry_liquidation->project_entry->entry){
                $purchase_product_id = $project_entry_liquidation->project_entry->entry->inventory->purchase_product_id;
            }else{
                $purchase_product_id = $project_entry_liquidation->project_entry->special_inventory->purchase_product_id;
            }

            $entry = Entry::create([
                'quantity' => $request->refund_quantity,
                'unitary_price' => $project_entry_liquidation->project_entry->unitary_price,
                'entry_date' => $today,
                'type' => 'retrieval'
            ]);

            RetrievalEntry::create([
                'project_entry_liquidation_id' => $project_entry_liquidation->id,
                'entry_id' => $entry->id,
                'purchase_product_id' => $purchase_product_id
            ]);
        }
    }
    
}
