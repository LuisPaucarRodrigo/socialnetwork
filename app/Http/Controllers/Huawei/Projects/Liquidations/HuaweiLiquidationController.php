<?php

namespace App\Http\Controllers\Huawei\Projects\Liquidations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HuaweiProject;
use App\Models\HuaweiProjectLiquidation;
use App\Models\HuaweiProjectResource;
use Inertia\Inertia;

class HuaweiLiquidationController extends Controller
{
    //liquidations
    public function geResourcesToLiquidate($huawei_project)
    {
        $project = HuaweiProject::find($huawei_project);
        $project_name = $project->name . ' / ' . $project->code;
        $equipments = HuaweiProjectResource::where('huawei_project_id', $huawei_project)
            ->where('quantity', '!=', 0)
            ->whereDoesntHave('huawei_project_liquidation')
            ->whereHas('huawei_entry_detail', function ($query) {
                $query->whereNull('huawei_material_id');
            })
            ->with('huawei_entry_detail.huawei_equipment_serie.huawei_equipment')
            ->get();

        $materials = HuaweiProjectResource::where('huawei_project_id', $huawei_project)
            ->where('quantity', '!=', 0)
            ->whereDoesntHave('huawei_project_liquidation')
            ->whereHas('huawei_entry_detail', function ($query) {
                $query->whereNull('huawei_equipment_serie_id');
            })
            ->with('huawei_entry_detail.huawei_material')
            ->get();

        $data = [
            'series' => $equipments->map(function ($resource) {
                return $resource->huawei_entry_detail->huawei_equipment_serie->serie_number ?? null;
            })->filter()->unique()->values(),
            'material_order_numbers' => $materials->map(function ($resource) {
                return $resource->huawei_entry_detail->order_number ?? null;
            })->filter()->unique()->values(),
            'equipment_order_numbers' => $equipments->map(function ($resource) {
                return $resource->huawei_entry_detail->order_number ?? null;
            })->filter()->unique()->values(),
        ];

        foreach ($equipments as $resource) {
            $resource->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name = $this->sanitizeText2($resource->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name);
        }

        foreach ($materials as $resource) {
            $resource->huawei_entry_detail->huawei_material->name = $this->sanitizeText2($resource->huawei_entry_detail->huawei_material->name);
        }

        return Inertia::render('Huawei/Projects/Liquidations/Liquidations', [
            'equipments' => $equipments,
            'materials' => $materials,
            'huawei_project' => $huawei_project,
            'project_name' => $project_name,
            'projectState' => $project->status,
            'data' => $data
        ]);
    }

    public function search_advance_liquidate($huawei_project, Request $request)
    {
        $equipments = HuaweiProjectResource::where('huawei_project_id', $huawei_project)
            ->where('quantity', '!=', 0)
            ->whereDoesntHave('huawei_project_liquidation')
            ->whereHas('huawei_entry_detail', function ($query) {
                $query->whereNull('huawei_material_id');
            })
            ->with('huawei_entry_detail.huawei_equipment_serie.huawei_equipment');

        $materials = HuaweiProjectResource::where('huawei_project_id', $huawei_project)
            ->where('quantity', '!=', 0)
            ->whereDoesntHave('huawei_project_liquidation')
            ->whereHas('huawei_entry_detail', function ($query) {
                $query->whereNull('huawei_equipment_serie_id');
            })
            ->with('huawei_entry_detail.huawei_material');

        $equipmentsForCount = $equipments->get();
        $materialsForCount = $materials->get();

        $data = [
            'series' => $equipmentsForCount->map(function ($resource) {
                return $resource->huawei_entry_detail->huawei_equipment_serie->serie_number ?? null;
            })->filter()->unique()->values()->count(),
            'material_order_numbers' => $materialsForCount->map(function ($resource) {
                return $resource->huawei_entry_detail->order_number ?? null;
            })->filter()->unique()->values()->count(),
            'equipment_order_numbers' => $equipmentsForCount->map(function ($resource) {
                return $resource->huawei_entry_detail->order_number ?? null;
            })->filter()->unique()->values()->count(),
        ];

        if (count($request->selectedSeries) < $data['series']) {
            $series = $request->selectedSeries;
            $equipments->whereHas('huawei_entry_detail.huawei_equipment_serie', function ($subQuery) use ($series) {
                $subQuery->whereIn('serie_number', $series);
            });
        }
        if (count($request->selectedEquipmentOrderNumbers) < $data['equipment_order_numbers']) {
            $equipment_order_numbers = $request->selectedEquipmentOrderNumbers;
            $equipments->whereHas('huawei_entry_detail', function ($subQuery) use ($equipment_order_numbers) {
                $subQuery->whereIn('order_number', $equipment_order_numbers);
            });
        }
        if (count($request->selectedMaterialOrderNumbers) < $data['material_order_numbers']) {
            $material_order_numbers = $request->selectedMaterialOrderNumbers;
            $materials->whereHas('huawei_entry_detail', function ($subQuery) use ($material_order_numbers) {
                $subQuery->whereIn('order_number', $material_order_numbers);
            });
        }

        $equipments = $equipments->get();
        $materials = $materials->get();

        foreach ($equipments as $equipment) {
            $equipment->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name = $this->sanitizeText2($equipment->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name);
        }

        foreach ($materials as $material) {
            $material->huawei_entry_detail->huawei_material->name = $this->sanitizeText2($material->huawei_entry_detail->huawei_material->name);
        }

        return response()->json(['materials' => $materials, 'equipments' => $equipments], 200);
    }

    public function liquidate(HuaweiProject $huawei_project, Request $request, $equipment = null)
    {

        if (!$huawei_project->status) {
            abort(403, 'Acción no permitida');
        }

        $huawei_project_resource = HuaweiProjectResource::with('huawei_project_liquidation')->find($request->huawei_project_resource_id);

        if (!$huawei_project_resource || $huawei_project_resource->quantity <= 0 || $huawei_project_resource->huawei_project_liquidation !== null) {
            abort(403, 'No se puede liquidar este recurso debido a restricciones.');
        }

        $data = $request->validate([
            'huawei_project_resource_id' => 'required',
            'instalation_date' => 'nullable',
            'liquidated_quantity' => [
                'nullable',
                function ($attribute, $value, $fail) use ($huawei_project_resource) {
                    if ($value !== null && $value > $huawei_project_resource->quantity) {
                        $fail('La cantidad liquidada debe ser menor o igual a la cantidad asignada del recurso.');
                    }
                },
            ],
        ]);

        if ($equipment) {
            HuaweiProjectLiquidation::create([
                'huawei_project_resource_id' => $request->huawei_project_resource_id,
                'instalation_date' => $request->instalation_date,
                'liquidated_quantity' => 1
            ]);
        } else {
            HuaweiProjectLiquidation::create([
                'huawei_project_resource_id' => $request->huawei_project_resource_id,
                'instalation_date' => $request->instalation_date,
                'liquidated_quantity' => $request->liquidated_quantity
            ]);
        }
    }

    public function massiveLiquidation(Request $request, $equipment = null)
    {
        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
            'instalation_date' => 'required|date',
        ]);

        foreach ($data['ids'] as $item) {
            $huawei_project_resource = HuaweiProjectResource::with('huawei_project_liquidation')->find($item);
            if (!$huawei_project_resource || $huawei_project_resource->quantity <= 0 || $huawei_project_resource->huawei_project_liquidation !== null) {
                abort(403, 'No se puede liquidar este recurso debido a restricciones.');
            }
            if ($equipment) {
                HuaweiProjectLiquidation::create([
                    'huawei_project_resource_id' => $item,
                    'instalation_date' => $request->instalation_date,
                    'liquidated_quantity' => 1
                ]);
            } else {
                HuaweiProjectLiquidation::create([
                    'huawei_project_resource_id' => $item,
                    'instalation_date' => $request->instalation_date,
                    'liquidated_quantity' => $huawei_project_resource->quantity
                ]);
            }
        }
        return redirect()->back();
    }

    public function liquidationsHistory($huawei_project, $equipment = null)
    {
        $project = HuaweiProject::find($huawei_project);
        $project_name = $project->name . ' / ' . $project->code;
        if ($equipment) {
            $liquidations = HuaweiProjectLiquidation::whereHas('huawei_project_resource.huawei_entry_detail', function ($query) {
                $query->whereNull('huawei_material_id');
            })
                ->whereHas('huawei_project_resource', function ($query) use ($huawei_project) {
                    $query->where('huawei_project_id', $huawei_project);
                })
                ->with('huawei_project_resource.huawei_entry_detail.huawei_equipment_serie.huawei_equipment')
                ->paginate(10);

            foreach ($liquidations as $resource) {
                $resource->huawei_project_resource->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name = $this->sanitizeText2($resource->huawei_project_resource->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name);
            }
        } else {
            $liquidations = HuaweiProjectLiquidation::whereHas('huawei_project_resource.huawei_entry_detail', function ($query) {
                $query->whereNull('huawei_equipment_serie_id');
            })
                ->whereHas('huawei_project_resource', function ($query) use ($huawei_project) {
                    $query->where('huawei_project_id', $huawei_project);
                })
                ->with('huawei_project_resource.huawei_entry_detail.huawei_material')
                ->paginate(10);
            foreach ($liquidations as $resource) {
                $resource->huawei_project_resource->huawei_entry_detail->huawei_material->name = $this->sanitizeText2($resource->huawei_project_resource->huawei_entry_detail->huawei_material->name);
            }
        }

        return Inertia::render('Huawei/Projects/Liquidations/LiquidationsHistory', [
            'liquidations' => $liquidations,
            'huawei_project' => $huawei_project,
            'equipment' => $equipment,
            'project_name' => $project_name
        ]);
    }
}
