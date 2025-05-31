<?php

namespace App\Services;

use App\Models\CicsaAssignation;
use App\Models\CostLine;
use Illuminate\Database\Eloquent\Builder;
use PhpParser\Node\Expr\Cast\Object_;

class CicsaServices
{
    public function getCostLine(String $cost_line): Object
    {
        $cost_line = CostLine::where('name', $cost_line)->with('cost_center')->first();
        return $cost_line;
    }

    public function cicsaBaseQuery($type): Builder
    {
        $list = [247, 253, 254];
        $query = CicsaAssignation::whereNotIn('id', $list)->whereHas('project', function ($subQuery) use ($type) {
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
        $result = $this->getCostLine($cost_line);
        return $result->cost_center;
    }
    // public function 
    public function addCalculatedFields($item)
    {
        $item->each(function ($it) {
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
        return $item;
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
            $projectsCicsa->where(function ($query) use ($search) {
                $this->searchBase($query, $search);
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

    public function baseAssignation($type): Builder
    {
        $assignation = $this->cicsaBaseQuery($type);
        $assignation->with('project.cost_center');
        return $assignation;
    }

    public function searchAssignation($request, $type): Builder
    {
        $assignation = $this->baseAssignation($type);

        $assignation->where(function ($query) use ($request) {
            $this->searchBase($query, $request->searchQuery);
        });

        return $assignation;
    }

    public function updateAssignation($validateData,$cicsa_assignation_id): Object
    {
        $cicsaAssignation = CicsaAssignation::updateOrCreate(
            ['id' => $cicsa_assignation_id],
            $validateData
        );
        return $cicsaAssignation;
    }

    public function baseFeasibilities($type): Builder
    {
        $feasibilities = $this->cicsaBaseQuery($type);
        $feasibilities->with(
            'cicsa_feasibility.cicsa_feasibility_materials',
            'project.cost_center'
        );
        return $feasibilities;
    }

    public function searchFeasibilities($request, $type): Builder
    {
        $feasibilities = $this->baseFeasibilities($type);

        $feasibilities->where(function ($query) use ($request) {
            $this->searchBase($query, $request->searchQuery);
        });

        return $feasibilities;
    }

    public function baseMaterial($type): Builder
    {
        $material = $this->cicsaBaseQuery($type);
        $material->with(
            'cicsa_feasibility.cicsa_feasibility_materials',
            'cicsa_materials.cicsa_material_items',
            'project.cost_center'
        );
        return $material;
    }

    public function searchMaterial($request, $type): Builder
    {
        $material = $this->baseMaterial($type);
        $material->where(function ($query) use ($request) {
            $this->searchBase($query, $request->searchQuery);
        });
        return $material;
    }

    public function baseInstallation($type): Builder
    {
        $installations = $this->cicsaBaseQuery($type);
        $installations->with(
            'cicsa_installation.cicsa_installation_materials',
            'cicsa_installation.user',
            'project.cost_center'
        );
        return $installations;
    }

    public function searchInstallation($request, $type): Builder
    {
        $material = $this->baseInstallation($type);
        $material->where(function ($query) use ($request) {
            $this->searchBase($query, $request->searchQuery);
        });
        return $material;
    }

    public function basePurchaseOrder($type): Builder
    {
        $purchaseOrder = $this->cicsaBaseQuery($type);
        $purchaseOrder->with('cicsa_installation', 'cicsa_purchase_order', 'project.cost_center');
        return $purchaseOrder;
    }

    public function searchPurchaseOrder($request, $type): Builder
    {
        $searchQuery = $request->searchQuery;
        $purchaseOrder = $this->basePurchaseOrder($type);
        $purchaseOrder->where(function ($query) use ($searchQuery) {
            $searchBase = $this->searchBase($query, $searchQuery);
            $searchBase->orWhere(function ($query) use ($searchQuery) {
                $query->whereHas('cicsa_purchase_order', function ($query) use ($searchQuery) {
                    $query->where('oc_number', 'like', "%$searchQuery%")
                        ->orWhere('observation', 'like', "%$searchQuery%");
                });
            });
        });
        return $purchaseOrder;
    }

    public function baseOCValidation($type): Builder
    {
        $purchaseValidations = $this->cicsaBaseQuery($type);
        $purchaseValidations->with([
            'cicsa_purchase_order_validation.cicsa_purchase_order' => function ($query) {
                $query->select('id', 'oc_number');
            },
            'project.cost_center'
        ]);
        return $purchaseValidations;
    }

    public function searchOCValidation($request, $type): Builder
    {
        $searchQuery = $request->searchQuery;
        $purchaseValidations = $this->baseOCValidation($type);
        $purchaseValidations->where(function ($query) use ($searchQuery) {
            $searchBase = $this->searchBase($query, $searchQuery);
            $searchBase->orWhereHas('cicsa_purchase_order', function ($query) use ($searchQuery) {
                $query->where('oc_number', 'like', "%$searchQuery%");
            })
                ->orWhereHas('cicsa_purchase_order_validation', function ($query) use ($searchQuery) {
                    $query->where('observations', 'like', "%$searchQuery%");
                });
        });
        return $purchaseValidations;
    }

    public function baseServiceOrder($type): Builder
    {
        $serviceOrders = $this->cicsaBaseQuery($type);
        $serviceOrders = $serviceOrders->with([
            'cicsa_service_order.cicsa_purchase_order' => function ($query) {
                $query->select('id', 'oc_number');
            },
            'project.cost_center'
        ]);
        return $serviceOrders;
    }

    public function searchServiceOrder($request, $type): Builder
    {
        $searchQuery = $request->searchQuery;
        $serviceOrders = $this->baseServiceOrder($type);
        $serviceOrders->where(function ($query) use ($searchQuery) {
            $searchBase = $this->searchBase($query, $searchQuery);
            $searchBase->orWhere(function ($query) use ($searchQuery) {
                $query->whereHas('cicsa_purchase_order', function ($query) use ($searchQuery) {
                    $query->where('oc_number', 'like', "%$searchQuery%");
                });
            });
        });
        return $serviceOrders;
    }

    public function baseChargeArea($type): Builder
    {
        $chargeAreas = $this->cicsaBaseQuery($type);
        $chargeAreas = $chargeAreas->with([
            'cicsa_charge_area.cicsa_purchase_order' => function ($query) {
                $query->select('id', 'oc_number')
                    ->with(['cicsa_service_order:id,document_invoice,cicsa_purchase_order_id']);
            },
            'project.cost_center'
        ]);
        return $chargeAreas;
    }

    public function searchChargeArea($request, $type): Builder
    {
        $searchQuery = $request->searchQuery;
        $chargeAreas = $this->baseChargeArea($type);
        $chargeAreas->where(function ($query) use ($searchQuery) {
            $searchBase = $this->searchBase($query, $searchQuery);
            $searchBase->orWhere(function ($query) use ($searchQuery) {
                $query->whereHas('cicsa_purchase_order', function ($query) use ($searchQuery) {
                    $query->where('oc_number', 'like', "%$searchQuery%");
                });
            })
                ->orWhere(function ($query) use ($searchQuery) {
                    $query->whereHas('cicsa_charge_area', function ($query) use ($searchQuery) {
                        $query->where('invoice_number', 'like', "%$searchQuery%");
                    });
                });
        });
        return $chargeAreas;
    }
}
