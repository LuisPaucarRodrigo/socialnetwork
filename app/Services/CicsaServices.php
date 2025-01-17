<?php

namespace App\Services;

use App\Models\CicsaAssignation;
use App\Models\CostLine;
use Illuminate\Database\Eloquent\Builder;

class CicsaServices
{
    public function costLine(): Object
    {
        $cost_line = CostLine::where('name', 'PEXT')->with('cost_center')->first();
        return $cost_line;
    }

    public function cicsaBaseQuery($type): Builder
    {
        $query = CicsaAssignation::whereHas('project', function ($subQuery) use ($type) {
            $subQuery->where('cost_line_id', $type);
        });
        return $query;
    }

    // public function 
    public function addRelations($item): Builder
    {
        $query = $item->each(function ($it) {
            $it->setAppends([
                'total_materials',
                'cicsa_project_status',
                'cicsa_administration_status',
                'cicsa_charge_status',
                'last_project_status_date',
                'last_administration_status_date',
                'last_charge_status_date',
            ]);
        });
        return $query;
    }

    public function addCalculatedFields($item): Builder
    {
        $query = $item->with(
            'cicsa_feasibility.cicsa_feasibility_materials',
            'cicsa_materials.cicsa_material_items',
            'cicsa_installation.cicsa_installation_materials',
            'cicsa_purchase_order',
            'cicsa_purchase_order_validation',
            'cicsa_service_order',
            'cicsa_charge_area',
            'project.cost_center'
        );
        return $query;
    }
}
