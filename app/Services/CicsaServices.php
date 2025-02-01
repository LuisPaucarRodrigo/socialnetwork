<?php

namespace App\Services;

use App\Models\CicsaAssignation;
use App\Models\CostLine;
use Faker\Core\Number;
use Illuminate\Database\Eloquent\Builder;

class CicsaServices
{
    public function getCostLine(String $cost_line): Object
    {
        $cost_line = CostLine::where('name', $cost_line)->with('cost_center')->first();
        return $cost_line;
    }

    public function cicsaBaseQuery($type): Builder
    {
        $query = CicsaAssignation::whereHas('project', function ($subQuery) use ($type) {
            $subQuery->where('cost_line_id', $type)
                ->where('is_accepted', 1);
        })
            ->orderBy('created_at', 'desc');
        return $query;
    }

    public function addRelations($item)
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

    public function differentialIndex(String $type)
    {
        $cost_line = $type == 2 ? 'PEXT' : 'PINT';
        // if ($type == 2) {
        //     $cost_line = CostLine::where('name', 'PEXT')->with('cost_center')->first();
        // }
        // if ($type == 1) {
        //     $cost_line = CostLine::where('name', 'PINT')->with('cost_center')->first();
        // }
        $result = $this->getCostLine($cost_line);
        return $result->cost_center;
    }
    // public function 
    public function addCalculatedFields($item)
    {
        $object = $item->getCollection()->each(function ($it) {
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
        return $object;
    }

    public function filteredAdvance($request, $projectsCicsa): Builder
    {
        if ($request->opNoDate) {
            $projectsCicsa->where('assignation_date', null);
        }
        if ($request->opStartDate) {
            $projectsCicsa->where('assignation_date', '>=', $request->opStartDate);
        }
        if ($request->opEndDate) {
            $projectsCicsa->where('assignation_date', '<=', $request->opEndDate);
        }
        if (!empty($request->search)) {
            $search = $request->search;
            $searchTerms = explode(' ', $search);
            $projectsCicsa = $projectsCicsa->where(function ($query) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    // $query->where('project_name', 'like', "%$term%")
                    //     ->orWhere('project_code', 'like', "%$term%")
                    //     ->orWhere('cpe', 'like', "%$term%");
                    $this->searchBase($query, $term);
                }
            });
        }
        if (count($request->cost_center) < 7) {
            $costCenter = $request->cost_center;
            $projectsCicsa = $projectsCicsa->whereHas('project.cost_center', function ($query) use ($costCenter) {
                $query->whereIn('name', $costCenter);
            });
        }
        return $projectsCicsa;
    }

    public function filteredCalculated($stages, $projectsCicsa)
    {
        if ($stages === "Administracion") {
            $projectsCicsa = $projectsCicsa->filter(function ($item) {
                return $item->cicsa_project_status === 'Completado';
            });
        } elseif ($stages === "Cobranza") {
            $projectsCicsa = $projectsCicsa->filter(function ($item) {
                return $item->cicsa_administration_status === 'Completado';
            });
        }
        return $projectsCicsa;
    }

    public function filteredExternal($request, $projectsCicsa)
    {
        if (count($request->state_charge_area) < 4) {
            $selectedPS = $request->state_charge_area;
            $projectsCicsa = $projectsCicsa->filter(function ($project) use ($selectedPS) {
                return $project->cicsa_charge_area->contains(function ($chargeArea) use ($selectedPS) {
                    return in_array($chargeArea->state, $selectedPS);
                });
            });
        }

        if (count($request->project_status) < 3) {
            $selectedPS = $request->project_status;
            $projectsCicsa = $projectsCicsa->filter(function ($item) use ($selectedPS) {
                return in_array($item->cicsa_project_status, $selectedPS);
            });
        }

        if (count($request->administration_status) < 3) {
            $selectedPS = $request->administration_status;
            $projectsCicsa = $projectsCicsa->filter(function ($item) use ($selectedPS) {
                return in_array($item->cicsa_administration_status, $selectedPS);
            });
        }

        if (count($request->charge_status) < 3) {
            $selectedPS = $request->charge_status;
            $projectsCicsa = $projectsCicsa->filter(function ($item) use ($selectedPS) {
                return in_array($item->cicsa_charge_status, $selectedPS);
            });
        }
        return $projectsCicsa;
    }

    public function searchBase($query, $searchQuery)
    {
        $query->where('project_name', 'like', "%$searchQuery%")
            ->orWhere('project_code', 'like', "%$searchQuery%")
            ->orWhere('cpe', 'like', "%$searchQuery%");
        return $query;
    }
}
