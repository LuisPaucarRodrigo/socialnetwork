<?php

namespace App\Http\Controllers\Huawei\HuaweiInventory;

use App\Constants\HuaweiConstants;
use App\Exports\HuaweiInventoryExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Huawei\Utils\HuaweiUtils;
use App\Models\HuaweiEntryDetail;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class HuaweiGeneralController extends Controller
{

    public function getGeneralEquipments($prefix)
    {
        $equipments = HuaweiEntryDetail::select('id', 'assigned_diu', 'new_site', 'order_date', 'entry_date', 'order_number', 'unit_price', 'huawei_order_id', 'huawei_entry_id', 'huawei_equipment_serie_id', 'observation')->whereNull('huawei_material_id')
            ->whereHas('huawei_equipment_serie.huawei_equipment', function ($query) use ($prefix) {
                $query->where('prefix', $prefix);
            })
            ->with([
                'huawei_entry',
                'huawei_equipment_serie.huawei_equipment:id,name,claro_code,prefix',  // Relación ya filtrada
                'latest_huawei_project_resource'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        $equipments->makeHidden([
            'available_quantity',
            'huawei_project_resources',
            'project_quantity',
            'refund_quantity'
        ]);

        $equipments->each(function ($equipment) {
            $equipment->huawei_equipment_serie->huawei_equipment->makeHidden(['available_quantity', 'quantity']);
            if ($equipment->latest_huawei_project_resource) {
                $equipment->latest_huawei_project_resource->makeHidden(['liquidated_quantity']);
            }
        });

        $data = [
            'order_numbers' => $equipments->pluck('order_number')->filter()->unique()->values()->toArray(),
            'guide_numbers' => $equipments->pluck('huawei_entry.guide_number')->filter()->unique()->values()->toArray(),
            'sap_codes' => $equipments->pluck('huawei_equipment_serie.huawei_equipment.claro_code')->filter()->unique()->values()->toArray(),
            'states' => $equipments->pluck('state')->filter()->unique()->values()->toArray(),
            'du_s' => $equipments->pluck('assigned_diu')->filter()->unique()->values()->toArray(),
            'sites' => $equipments->map(function ($equipment) {
                return [$equipment->assigned_site, $equipment->new_site];
            })->flatten()->filter()->unique()->values()->toArray(),
        ];

        foreach ($equipments as $equipment) {
            $equipment->huawei_equipment_serie->huawei_equipment->name = HuaweiUtils::sanitizeText2($equipment->huawei_equipment_serie->huawei_equipment->name);
        }

        return Inertia::render('Huawei/HuaweiInventory/General/GeneralEquipments', [
            'equipments' => $equipments,
            'warehouse' => $prefix,
            'data' => $data,
            'operators' => HuaweiConstants::getOperators(),
        ]);
    }

    public function searchGeneralEquipments($prefix, $request)
    {
        $searchTerm = strtolower($request);

        // Consulta principal, incluyendo filtro de `prefix` y campos de búsqueda especificados
        $equipments = HuaweiEntryDetail::select('id', 'assigned_diu', 'new_site', 'order_date', 'entry_date', 'order_number', 'unit_price', 'huawei_order_id', 'huawei_entry_id', 'huawei_equipment_serie_id', 'observation')->whereNull('huawei_material_id')
            ->whereHas('huawei_equipment_serie.huawei_equipment', function ($query) use ($prefix) {
                $query->where('prefix', $prefix);
            })
            ->where(function ($query) use ($searchTerm) {
                // Campos de búsqueda específicos
                $query->whereRaw('LOWER(assigned_diu) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(new_site) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(order_number) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(observation) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereHas('huawei_entry', function ($subQuery) use ($searchTerm) {
                    $subQuery->whereRaw('LOWER(guide_number) LIKE ?', ["%{$searchTerm}%"]);
                })
                    ->orWhereHas('huawei_equipment_serie', function ($subQuery) use ($searchTerm) {
                    $subQuery->whereRaw('LOWER(serie_number) LIKE ?', ["%{$searchTerm}%"])
                        ->orWhereHas('huawei_equipment', function ($innerQuery) use ($searchTerm) {
                            $innerQuery->whereRaw('LOWER(claro_code) LIKE ?', ["%{$searchTerm}%"])
                                ->orWhereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"]);
                        });
                });
            })
            ->with([
                'huawei_entry',
                'huawei_equipment_serie.huawei_equipment:id,name,claro_code,prefix',
                'latest_huawei_project_resource'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Consulta adicional para los filtros por campos calculados
        $equipmentsToFilter = HuaweiEntryDetail::select('id', 'assigned_diu', 'new_site', 'order_date', 'entry_date', 'order_number', 'unit_price', 'huawei_order_id', 'huawei_entry_id', 'huawei_equipment_serie_id', 'observation')->whereNull('huawei_material_id')
            ->whereHas('huawei_equipment_serie.huawei_equipment', function ($query) use ($prefix) {
                $query->where('prefix', $prefix);
            })
            ->with([
                'huawei_entry',
                'huawei_equipment_serie.huawei_equipment:id,name,claro_code,prefix',
                'latest_huawei_project_resource'
            ])->orderBy('created_at', 'desc')->get();

        // Filtro por `state` y `instalation_state`
        $stateFilteredEquipments = $equipmentsToFilter->filter(function ($detail) use ($searchTerm) {
            return str_contains(strtolower($detail->state), $searchTerm) || str_contains(strtolower($detail->instalation_state), $searchTerm);
        });

        // Filtro por `assigned_site`
        $assignedSiteFilteredEquipments = $equipmentsToFilter->filter(function ($detail) use ($searchTerm) {
            return str_contains(strtolower($detail->assigned_site), $searchTerm);
        });

        // Combinación de todos los resultados, eliminando duplicados
        $finalEquipments = $equipments
            ->merge($stateFilteredEquipments)
            ->merge($assignedSiteFilteredEquipments)
            ->unique('id');

        // Sanitización del campo `name` en `huawei_equipment`
        foreach ($finalEquipments as $equipment) {
            if ($equipment->huawei_equipment_serie && $equipment->huawei_equipment_serie->huawei_equipment) {
                $equipment->huawei_equipment_serie->huawei_equipment->name = HuaweiUtils::sanitizeText2($equipment->huawei_equipment_serie->huawei_equipment->name);
            }
        }

        $finalEquipments->makeHidden([
            'available_quantity',
            'huawei_project_resources',
            'project_quantity',
            'refund_quantity'
        ]);

        $finalEquipments->each(function ($equipment) {
            $equipment->huawei_equipment_serie->huawei_equipment->makeHidden(['available_quantity', 'quantity']);
            if ($equipment->latest_huawei_project_resource) {
                $equipment->latest_huawei_project_resource->makeHidden(['liquidated_quantity']);
            }
        });

        $data = [
            'order_numbers' => $equipments->pluck('order_number')->filter()->unique()->values()->toArray(),
            'guide_numbers' => $equipments->pluck('huawei_entry.guide_number')->filter()->unique()->values()->toArray(),
            'sap_codes' => $equipments->pluck('huawei_equipment_serie.huawei_equipment.claro_code')->filter()->unique()->values()->toArray(),
            'states' => $equipments->pluck('state')->filter()->unique()->values()->toArray(),
            'du_s' => $equipments->pluck('assigned_diu')->filter()->unique()->values()->toArray(),
            'sites' => $equipments->map(function ($equipment) {
                return [$equipment->assigned_site, $equipment->new_site];
            })->flatten()->filter()->unique()->values()->toArray(),
        ];

        return Inertia::render('Huawei/HuaweiInventory/General/GeneralEquipments', [
            'equipments' => $finalEquipments,
            'search' => $request,
            'warehouse' => $prefix,
            'data' => $data,
            'operators' => HuaweiConstants::getOperators(),
        ]);
    }

    public function searchGeneralAdvance($prefix, Request $request)
    {
        // Construimos la consulta base
        $query = HuaweiEntryDetail::select('id', 'assigned_diu', 'new_site', 'order_date', 'entry_date', 'order_number', 'unit_price', 'huawei_order_id', 'huawei_entry_id', 'huawei_equipment_serie_id', 'observation')->whereNull('huawei_material_id')
            ->whereHas('huawei_equipment_serie.huawei_equipment', function ($query) use ($prefix) {
                $query->where('prefix', $prefix);
            })
            ->with([
                'huawei_entry',
                'huawei_equipment_serie.huawei_equipment:id,name,claro_code,prefix',
                'latest_huawei_project_resource'
            ]);

        // Contar los elementos en bruto
        $equipmentsForCounts = $query->get();


        $equipmentsForCounts->makeHidden([
            'available_quantity',
            'huawei_project_resources',
            'project_quantity',
            'refund_quantity'
        ]);

        $equipmentsForCounts->each(function ($equipment) {
            $equipment->huawei_equipment_serie->huawei_equipment->makeHidden(['available_quantity', 'quantity']);
            if ($equipment->latest_huawei_project_resource) {
                $equipment->latest_huawei_project_resource->makeHidden(['liquidated_quantity']);
            }
        });

        $data = [
            'order_numbers' => $equipmentsForCounts->pluck('order_number')->filter()->unique()->count(),
            'guide_numbers' => $equipmentsForCounts->pluck('huawei_entry.guide_number')->filter()->unique()->count(),
            'sap_codes' => $equipmentsForCounts->pluck('huawei_equipment_serie.huawei_equipment.claro_code')->filter()->unique()->count(),
            'states' => $equipmentsForCounts->pluck('state')->filter()->unique()->count(),
            'du_s' => $equipmentsForCounts->pluck('assigned_diu')->filter()->unique()->count(),
            'sites' => $equipmentsForCounts->map(function ($equipment) {
                return [$equipment->assigned_site, $equipment->new_site];
            })->flatten()->filter()->unique()->count(),
        ];

        // Aplicar el filtro si corresponde
        if (count($request->selectedOrderNumbers) < $data['order_numbers']) {
            $query->whereIn('order_number', $request->selectedOrderNumbers);
        }

        if (count($request->selectedGuideNumbers) < $data['guide_numbers'] + 1) {
            $guide_numbers = $request->selectedGuideNumbers;

            $query->where(function ($query) use ($guide_numbers) {
                if (!in_array('(vacio)', $guide_numbers)) {
                    $query->whereNotNull('huawei_entry_id')->whereHas('huawei_entry', function ($subQuery) use ($guide_numbers) {
                        $subQuery->whereIn('guide_number', $guide_numbers);
                    });
                } else {
                    $query->whereHas('huawei_entry', function ($subQuery) use ($guide_numbers) {
                        $subQuery->whereIn('guide_number', $guide_numbers);
                    })->orWhereNull('huawei_entry_id');
                }
            });
        }

        if (count($request->selectedSAPCodes) < $data['sap_codes']) {
            $sap_codes = $request->selectedSAPCodes;
            $query->whereHas('huawei_equipment_serie.huawei_equipment', function ($subQuery) use ($sap_codes) {
                $subQuery->whereIn('claro_code', $sap_codes);
            });
        }

        if (count($request->selectedDUs) < $data['du_s']) {
            $du_s = $request->selectedDUs;
            $query->whereIn('assigned_diu', $du_s);
        }

        // Obtener los datos finales con los filtros aplicados
        $equipments = $query->orderBy('created_at', 'desc')->get();


        $equipments->makeHidden([
            'available_quantity',
            'huawei_project_resources',
            'project_quantity',
            'refund_quantity'
        ]);

        $equipments->each(function ($equipment) {
            $equipment->huawei_equipment_serie->huawei_equipment->makeHidden(['available_quantity', 'quantity']);
            if ($equipment->latest_huawei_project_resource) {
                $equipment->latest_huawei_project_resource->makeHidden(['liquidated_quantity']);
            }
        });

        foreach ($equipments as $equipment) {
            if ($equipment->huawei_equipment_serie && $equipment->huawei_equipment_serie->huawei_equipment) {
                $equipment->huawei_equipment_serie->huawei_equipment->name = HuaweiUtils::sanitizeText2($equipment->huawei_equipment_serie->huawei_equipment->name);
            }
        }
        return response()->json(["equipments" => $equipments], 200);
    }

    public function generalMassiveUpdate(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
            'entry_date' => 'nullable|date',
            'new_site' => 'nullable',
            'assigned_diu' => 'nullable',
        ]);

        $updateData = array_filter([
            'entry_date' => $data['entry_date'],
            'new_site' => $data['new_site'],
            'assigned_diu' => $data['assigned_diu'],
        ], function ($value) {
            return !is_null($value);
        });

        $entries = HuaweiEntryDetail::whereIn('id', $data['ids']);
        foreach ($entries as $entry) {
            if ($entry->state == 'Devuelto') {
                abort(403, 'Acción no permitida');
            }
        }

        if (empty($updateData)) {
            return response()->json(['message' => 'No fields to update'], 400);
        }

        HuaweiEntryDetail::whereIn('id', $data['ids'])->update($updateData);

        $updatedDetails = HuaweiEntryDetail::whereIn('id', $data['ids'])->with([
            'huawei_entry',
            'huawei_equipment_serie.huawei_equipment',
            'latest_huawei_project_resource.huawei_project'
        ])->get();

        return response()->json($updatedDetails, 200);
    }
    public function exportInventory()
    {
        return Excel::download(new HuaweiInventoryExport(), 'Inventario Huawei.xlsx');
    }


}