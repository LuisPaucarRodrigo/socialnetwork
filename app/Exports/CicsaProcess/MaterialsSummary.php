<?php

namespace App\Exports\CicsaProcess;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Models\CicsaAssignation;
use App\Models\CicsaMaterial;
use App\Models\CicsaInstallationMaterial;
use App\Models\CicsaMaterialsItem;

class MaterialsSummary implements FromView
{
    
    protected $ca_id;

    public function __construct($ca_id)
    {
        $this->ca_id = $ca_id;
    }

    public function view():View
    {
        $project = CicsaAssignation::find($this->ca_id);

        $materials = CicsaMaterialsItem::with('cicsa_material.cicsa_assignation')
            ->whereHas('cicsa_material',function($query) {
                return $query->where('cicsa_assignation_id', $this->ca_id);
            })
            ->get();
            
        $installedMaterials = CicsaInstallationMaterial::whereHas('cicsa_installation', function($query){
            return $query->where('cicsa_assignation_id', $this->ca_id);
        })->get()->values()->all();
        
        $materials_guides = CicsaMaterial::where('cicsa_assignation_id', $this->ca_id)
            ->orderBy('pick_date')->get();

        $true_materials = [];
        foreach($materials as $item){
            $key = array_search($item->name, array_column($true_materials, 'name'));
            if($key !== false){
                $true_materials[$key][$item->cicsa_material_id] = $item->quantity;
                $true_materials[$key]['total_quantity'] += $item->quantity;;
            } else {
                $key_2 = array_search($item->name, array_column($installedMaterials, 'name'));
                $used_quantity = null;
                if($key_2!==false){
                    $used_quantity = $installedMaterials[$key_2]->used_quantity;
                }
                array_push($true_materials,[
                    'code_ax'=>$item->code_ax,
                    'internal_reference' => $item->internal_reference,
                    'name'=> $item->name,
                    $item->cicsa_material_id => $item->quantity,
                    'unit'=>$item->unit,
                    'used_quantity'=>$used_quantity,
                    'total_quantity' => $item->quantity
                ]);
            }
        }


        return view('Export/MaterialSummary', [
            'materials' => $true_materials,
            'materials_guides' => $materials_guides,
            'project' => $project,
        ]);
    }
}
