<?php

namespace App\Http\Controllers\Huawei\HuaweiInventory;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Huawei\Utils\HuaweiUtils;
use App\Models\HuaweiEntryDetail;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HuaweiDetailsController extends Controller
{
    public function showDetails($id, $equipment = null)
    {
        if ($equipment) {
            $entries = HuaweiEntryDetail::select(
                'id',
                'new_site',
                'assigned_diu',
                'order_number',
                'order_date',
                'entry_date',
                'unit_price',
                'huawei_order_id',
                'huawei_entry_id',
                'quantity',
                'observation',
                'huawei_equipment_serie_id'
            )
                ->whereNull('huawei_material_id') // Excluir registros con huawei_material_id no nulo
                ->whereHas('huawei_equipment_serie.huawei_equipment', function ($query) use ($id) {
                    $query->where('id', $id);
                })
                ->with([
                    'huawei_entry:id,guide_number,observation,entry_date',
                    'huawei_pending_order:id,order_number,order_date,observation',
                    'huawei_equipment_serie.huawei_equipment:id,name',
                    'latest_huawei_project_resource.huawei_project:id,name,assigned_diu,ot'
                ])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            // Ocultar atributos en relaciones
            $entries->getCollection()->each(function ($equipment) {
                $equipment->makeHidden(['huawei_project_resources', 'project_quantity', 'refund_quantity', 'available_quantity']);
                $equipment->latest_huawei_project_resource?->huawei_project->makeHidden([
                    'state',
                    'additional_cost_total',
                    'static_cost_total',
                    'materials_in_project',
                    'equipments_in_project',
                    'materials_liquidated',
                    'equipments_liquidated',
                    'total_earnings',
                    'total_real_earnings',
                    'total_real_earnings_without_deposit',
                    'total_project_cost',
                    'total_employee_costs',
                    'total_essalud_employee_cost'
                ]);
                $equipment->huawei_equipment_serie->huawei_equipment->makeHidden(['huawei_entry_details']);
            });

            foreach ($entries as $entry) {
                $entry->huawei_equipment_serie->huawei_equipment->name = HuaweiUtils::sanitizeText2($entry->huawei_equipment_serie->huawei_equipment->name);
            }
        } else {
            $entries = HuaweiEntryDetail::select('id', 'new_site', 'assigned_diu', 'order_number', 'order_date', 'entry_date', 'unit_price', 'huawei_order_id', 'huawei_entry_id', 'quantity', 'observation', 'huawei_material_id')->where('huawei_material_id', $id)
                ->with('huawei_entry:id,guide_number,observation,entry_date', 'huawei_pending_order:id,order_number,order_date,observation', 'huawei_material:id,name', 'huawei_project_resources.huawei_project:id,assigned_diu', 'huawei_project_resources.huawei_project_liquidation')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $entries->getCollection()->each(function ($material) {
                $material->huawei_material->makeHidden(['quantity', 'huawei_entry_details', 'available_quantity']);
            });

            foreach ($entries as $entry) {
                $entry->huawei_material->name = HuaweiUtils::sanitizeText2($entry->huawei_material->name);
                $entry->makeHidden(['latest_huawei_project_resource']);
                foreach ($entry->huawei_project_resources as $resource) {
                    $resource->huawei_project->makeHidden([
                        'state',
                        'additional_cost_total',
                        'static_cost_total',
                        'materials_in_project',
                        'equipments_in_project',
                        'materials_liquidated',
                        'equipments_liquidated',
                        'total_earnings',
                        'total_real_earnings',
                        'total_real_earnings_without_deposit',
                        'total_project_cost',
                        'total_employee_costs',
                        'total_essalud_employee_cost'
                    ]);
                }
            }
        }

        return Inertia::render('Huawei/HuaweiInventory/Details/Details', [
            'entries' => $entries,
            'equipment' => $equipment,
            'id' => $id
        ]);
    }
    public function detailsWithoutDiu($id)
    {
        $entries = HuaweiEntryDetail::select(
            'id',
            'new_site',
            'assigned_diu',
            'order_number',
            'order_date',
            'entry_date',
            'unit_price',
            'huawei_order_id',
            'huawei_entry_id',
            'quantity',
            'observation',
            'huawei_equipment_serie_id'
        )
            ->whereNull('assigned_diu')
            ->whereHas('huawei_equipment_serie', function ($query) use ($id) {
                $query->whereHas('huawei_equipment', function ($query) use ($id) {
                    $query->where('id', $id);
                });
            })
            ->with([
                'huawei_entry:id,guide_number,observation,entry_date',
                'huawei_pending_order:id,order_number,order_date,observation',
                'huawei_equipment_serie.huawei_equipment:id,name',
                'latest_huawei_project_resource.huawei_project:id,name,assigned_diu,ot'
            ])->orderBy('created_at', 'desc')
            ->get();

        $entries->each(function ($equipment) {
            $equipment->makeHidden(['huawei_project_resources', 'project_quantity', 'refund_quantity', 'available_quantity']);
            $equipment->latest_huawei_project_resource?->huawei_project->makeHidden([
                'state',
                'additional_cost_total',
                'static_cost_total',
                'materials_in_project',
                'equipments_in_project',
                'materials_liquidated',
                'equipments_liquidated',
                'total_earnings',
                'total_real_earnings',
                'total_real_earnings_without_deposit',
                'total_project_cost',
                'total_employee_costs',
                'total_essalud_employee_cost'
            ]);
            $equipment->huawei_equipment_serie->huawei_equipment->makeHidden(['huawei_entry_details']);
        });

        foreach ($entries as $entry) {
            $entry->huawei_equipment_serie->huawei_equipment->name = HuaweiUtils::sanitizeText2($entry->huawei_equipment_serie->huawei_equipment->name);
        }

        return Inertia::render('Huawei/HuaweiInventory/Details/Details', [
            'entries' => $entries,
            'equipment' => 1,
            'id' => $id,
            'nodiu' => 1
        ]);
    }

    public function search($id, $request, $equipment = null)
    {
        $searchTerm = strtolower($request);

        // Consulta base
        $query = HuaweiEntryDetail::query();

        if ($equipment) {
            $query->whereHas('huawei_equipment_serie', function ($subQuery) use ($id) {
                $subQuery->where('huawei_equipment_id', $id);
            })->whereNull('huawei_material_id');

            $query->where(function ($filterQuery) use ($searchTerm) {
                $filterQuery->whereRaw('LOWER(assigned_diu) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(order_number) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereHas('huawei_entry', function ($subFilterQuery) use ($searchTerm) {
                        $subFilterQuery->whereRaw('LOWER(guide_number) LIKE ?', ["%{$searchTerm}%"]);
                    })
                    ->orWhereHas('huawei_equipment_serie', function ($subQuery) use ($searchTerm) {
                        $subQuery->whereRaw('LOWER(serie_number) LIKE ?', ["%{$searchTerm}%"]);
                    })
                    ->orWhereHas('huawei_project_resources.huawei_project', function ($projectQuery) use ($searchTerm) {
                        $projectQuery->whereRaw('LOWER(assigned_diu) LIKE ?', ["%{$searchTerm}%"]);
                    });
            });

            $queryByCode = HuaweiEntryDetail::query()
                ->whereHas('huawei_equipment_serie', function ($query) use ($searchTerm, $id) {
                    $query->where('huawei_equipment_id', $id);
                })
                ->whereNull('huawei_material_id')
                ->with(['huawei_entry', 'huawei_pending_order', 'huawei_equipment_serie.huawei_equipment', 'latest_huawei_project_resource.huawei_project'])
                ->get()
                ->filter(function ($detail) use ($searchTerm) {
                    return $detail->latest_huawei_project_resource &&
                        $detail->latest_huawei_project_resource->huawei_project &&
                        str_contains(strtolower($detail->latest_huawei_project_resource->huawei_project->code), $searchTerm);
                });

            $mergedResults = $query
                ->select(
                    'id',
                    'new_site',
                    'assigned_diu',
                    'order_number',
                    'order_date',
                    'entry_date',
                    'unit_price',
                    'huawei_order_id',
                    'huawei_entry_id',
                    'quantity',
                    'observation',
                    'huawei_equipment_serie_id'
                )
                ->with([
                    'huawei_entry:id,guide_number,observation,entry_date',
                    'huawei_pending_order:id,order_number,order_date,observation',
                    'huawei_equipment_serie.huawei_equipment:id,name',
                    'latest_huawei_project_resource.huawei_project:id,assigned_diu'
                ])
                ->orderBy('created_at', 'desc')
                ->get()
                ->merge($queryByCode)
                ->unique('id');

            $mergedResults->each(function ($equipment) {
                $equipment->makeHidden(['huawei_project_resources', 'project_quantity', 'refund_quantity', 'available_quantity']);
                $equipment->latest_huawei_project_resource?->huawei_project->makeHidden([
                    'state',
                    'additional_cost_total',
                    'static_cost_total',
                    'materials_in_project',
                    'equipments_in_project',
                    'materials_liquidated',
                    'equipments_liquidated',
                    'total_earnings',
                    'total_real_earnings',
                    'total_real_earnings_without_deposit',
                    'total_project_cost',
                    'total_employee_costs',
                    'total_essalud_employee_cost'
                ]);
                $equipment->huawei_equipment_serie->huawei_equipment->makeHidden(['huawei_entry_details']);
            });
            foreach ($mergedResults as $entry) {
                $entry->huawei_equipment_serie->huawei_equipment->name = HuaweiUtils::sanitizeText2($entry->huawei_equipment_serie->huawei_equipment->name);
            }

        } else {
            // Filtrar por material
            $query = HuaweiEntryDetail::select(
                'id',
                'new_site',
                'assigned_diu',
                'order_number',
                'order_date',
                'entry_date',
                'unit_price',
                'huawei_order_id',
                'huawei_entry_id',
                'quantity',
                'observation',
                'huawei_material_id'
            )
                ->whereNull('huawei_equipment_serie_id') // Asegura que no tengan relación con huawei_equipment_serie
                ->where('huawei_material_id', $id) // Filtra por el ID de huawei_material
                ->where(function ($query) use ($searchTerm) {
                    $query->whereRaw('LOWER(order_number) LIKE ?', ["%{$searchTerm}%"]) // Búsqueda por order_number
                        ->orWhereHas('huawei_entry', function ($entryQuery) use ($searchTerm) {
                            $entryQuery->whereRaw('LOWER(guide_number) LIKE ?', ["%{$searchTerm}%"]); // Búsqueda por guide_number
                        });
                });

            // Carga de relaciones
            $mergedResults = $query->with([
                'huawei_entry:id,guide_number,observation,entry_date',
                'huawei_pending_order:id,order_number,order_date,observation',
                'huawei_material:id,name',
                'huawei_project_resources.huawei_project:id,name,assigned_diu,ot',
                'huawei_project_resources.huawei_project_liquidation'
            ])
                ->orderBy('created_at', 'desc')
                ->get();


            $mergedResults->each(function ($material) {
                $material->huawei_material->makeHidden(['quantity', 'huawei_entry_details', 'available_quantity']);
            });

            foreach ($mergedResults as $entry) {
                $entry->huawei_material->name = HuaweiUtils::sanitizeText2($entry->huawei_material->name);
                $entry->makeHidden(['latest_huawei_project_resource']);
                foreach ($entry->huawei_project_resources as $resource) {
                    $resource->huawei_project->makeHidden([
                        'state',
                        'additional_cost_total',
                        'static_cost_total',
                        'materials_in_project',
                        'equipments_in_project',
                        'materials_liquidated',
                        'equipments_liquidated',
                        'total_earnings',
                        'total_real_earnings',
                        'total_real_earnings_without_deposit',
                        'total_project_cost',
                        'total_employee_costs',
                        'total_essalud_employee_cost'
                    ]);
                }
            }
        }

        return Inertia::render('Huawei/HuaweiInventory/Details/Details', [
            'entries' => $mergedResults,
            'equipment' => $equipment,
            'id' => $id,
            'search' => $request
        ]);
    }


    public function assignDIU(Request $request)
    {
        $request->validate([
            'huawei_entry_detail_id' => 'required',
            'assigned_diu' => 'required'
        ]);

        $entry_detail = HuaweiEntryDetail::find($request->huawei_entry_detail_id);
        if ($entry_detail->state !== 'Disponible') {
            abort(403, 'Acción no permitida.');
        }

        $entry_detail->update([
            'assigned_diu' => $request->assigned_diu
        ]);

        return redirect()->back();
    }
}