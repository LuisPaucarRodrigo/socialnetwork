<?php

namespace App\Http\Controllers\Huawei\Projects\Resources;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Huawei\Utils\HuaweiUtils;
use App\Models\HuaweiEntryDetail;
use App\Models\HuaweiEquipment;
use App\Models\HuaweiMaterial;
use Illuminate\Http\Request;
use App\Models\HuaweiProject;
use App\Models\HuaweiProjectResource;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class HuaweiResourceController extends Controller
{
    public function getResources($huawei_project, $equipment = null)
    {
        $found_project = HuaweiProject::find($huawei_project);

        $query = HuaweiProjectResource::where('huawei_project_id', $huawei_project)->with('huawei_project_liquidation');
        $project_state = HuaweiProject::find($huawei_project)->status;
        $huawei_project_name_code = HuaweiProject::find($huawei_project)->name . ' / ' . HuaweiProject::find($huawei_project)->code;
        if ($equipment) {
            $query->with(['huawei_entry_detail.huawei_equipment_serie.huawei_equipment'])
                ->whereHas('huawei_entry_detail', function ($query) {
                    $query->whereNotNull('huawei_equipment_serie_id')
                        ->whereNull('huawei_material_id');
                });
            $resources = $query->paginate(10);

            foreach ($resources as $resource) {
                $resource->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name = HuaweiUtils::sanitizeText2($resource->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name);
            }

            $equipments = HuaweiEquipment::where('prefix', $found_project->prefix)->with([
                'huawei_equipment_series.huawei_entry_detail' => function ($query) use ($found_project) {
                    $query->where('assigned_diu', $found_project->assigned_diu);
                }
            ])->get();

            $filteredEquipments = $equipments->filter(function ($equipment) {
                if ($equipment->huawei_equipment_series) {
                    foreach ($equipment->huawei_equipment_series as $serie) {
                        if ($serie->huawei_entry_detail) {
                            if ($serie->huawei_entry_detail->state === 'Disponible') {
                                return true;
                            }
                        }
                    }
                }
                return false;
            })
                ->map(function ($equipment) {
                    $equipment->name = HuaweiUtils::sanitizeText2($equipment->name); // Aplica la función de sanitización al nombre
                    return $equipment;
                })
                ->values()->toArray();

            return Inertia::render('Huawei/Projects/Resources/Resources', [
                'resources' => $resources,
                'equipment' => $equipment,
                'equipments' => $filteredEquipments,
                'huawei_project' => $huawei_project,
                'huawei_project_name_code' => $huawei_project_name_code,
                'project_state' => $project_state
            ]);
        } else {
            $query->with(['huawei_entry_detail.huawei_material'])
                ->whereHas('huawei_entry_detail', function ($query) {
                    $query->whereNotNull('huawei_material_id')
                        ->whereNull('huawei_equipment_serie_id');
                });
            $resources = $query->paginate(10);

            foreach ($resources as $resource) {
                $resource->huawei_entry_detail->huawei_material->name = HuaweiUtils::sanitizeText2($resource->huawei_entry_detail->huawei_material->name);
            }

            $materials = HuaweiMaterial::where('prefix', $found_project->prefix)
                ->get()
                ->filter(function ($material) {
                    return $material->available_quantity > 0;
                })
                ->map(function ($material) {
                    $material->name = HuaweiUtils::sanitizeText2($material->name); // Aplica la función de sanitización al nombre
                    return $material;
                })
                ->values()
                ->toArray();

            return Inertia::render('Huawei/Projects/Resources/Resources', [
                'resources' => $resources,
                'equipment' => $equipment,
                'materials' => $materials,
                'huawei_project' => $huawei_project,
                'huawei_project_name_code' => $huawei_project_name_code,
                'project_state' => $project_state
            ]);
        }
    }

    public function searchEntryDetails(HuaweiProject $huawei_project, $id, $equipment = null)
    {
        if ($equipment) {
            $entry_details = HuaweiEntryDetail::whereNull('huawei_material_id')->where('assigned_diu', $huawei_project->assigned_diu)
                ->whereHas('huawei_equipment_serie', function ($query) use ($id) {
                    $query->where('huawei_equipment_id', $id);
                })->with([
                    'huawei_equipment_serie' => function ($query) {
                        $query->select('id', 'serie_number');
                    }
                ])->get()
                ->filter(function ($detail) {
                    return $detail->state == 'Disponible';
                })->map(function ($detail) {
                    return [
                        'id' => $detail->id,
                        'serie_number' => $detail->huawei_equipment_serie->serie_number,
                        'order_number' => $detail->order_number
                    ];
                });
        } else {
            $entry_details = HuaweiEntryDetail::select(
                'id',
                'huawei_material_id',
                'order_number',
                'quantity'
            )
                ->where('huawei_material_id', $id)
                ->get()
                ->makeHidden([
                    'refund_quantity',
                    'project_quantity',
                    'assigned_site',
                    'antiquation_state',
                    'instalation_state',
                    'huawei_project_resources'
                ])
                ->filter(function ($detail) {
                    return $detail->state == 'Disponible';
                });
        }
        return response()->json(['details' => $entry_details]);
    }

    public function searchResources($huawei_project, $request, $equipment = null)
    {
        $found_project = HuaweiProject::find($huawei_project);

        $searchTerm = strtolower($request);
        $query = HuaweiProjectResource::where('huawei_project_id', $huawei_project);
        $project_state = HuaweiProject::find($huawei_project)->status;
        $huawei_project_name_code = HuaweiProject::find($huawei_project)->name . ' / ' . HuaweiProject::find($huawei_project)->code;

        if ($equipment) {
            $query->with(['huawei_entry_detail.huawei_equipment_serie.huawei_equipment'])
                ->whereHas('huawei_entry_detail', function ($query) use ($searchTerm) {
                    $query->whereNotNull('huawei_equipment_serie_id')
                        ->whereNull('huawei_material_id')
                        ->whereHas('huawei_equipment_serie.huawei_equipment', function ($query) use ($searchTerm) {
                            $query->where('name', 'like', '%' . $searchTerm . '%');
                        })
                        ->orWhereHas('huawei_equipment_serie', function ($query) use ($searchTerm) {
                            $query->where('serie_number', 'like', '%' . $searchTerm . '%');
                        });
                });
            $resources = $query->get();
            foreach ($resources as $resource) {
                $resource->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name = HuaweiUtils::sanitizeText2($resource->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name);
            }
            $equipments = HuaweiEquipment::with([
                'huawei_equipment_series.huawei_entry_detail' => function ($query) use ($found_project) {
                    $query->where('assigned_diu', $found_project->assigned_diu);
                }
            ])->get();

            $filteredEquipments = $equipments->filter(function ($equipment) {
                if ($equipment->huawei_equipment_series) {
                    foreach ($equipment->huawei_equipment_series as $serie) {
                        if ($serie->huawei_entry_detail) {
                            if ($serie->huawei_entry_detail->state === 'Disponible') {
                                return true;
                            }
                        }
                    }
                }
                return false;
            })
                ->map(function ($equipment) {
                    $equipment->name = HuaweiUtils::sanitizeText2($equipment->name); // Aplica la función de sanitización al nombre
                    return $equipment;
                })
                ->values()->toArray();

            return Inertia::render('Huawei/Projects/Resources/Resources', [
                'resources' => $resources,
                'equipment' => $equipment,
                'equipments' => $filteredEquipments,
                'huawei_project' => $huawei_project,
                'huawei_project_name_code' => $huawei_project_name_code,
                'project_state' => $project_state,
                'search' => $request
            ]);
        } else {
            $query->with(['huawei_entry_detail.huawei_material'])
                ->whereHas('huawei_entry_detail', function ($query) use ($searchTerm) {
                    $query->whereNotNull('huawei_material_id')
                        ->whereNull('huawei_equipment_serie_id')
                        ->whereHas('huawei_material', function ($query) use ($searchTerm) {
                            $query->where('name', 'like', '%' . $searchTerm . '%');
                        });
                });
            $resources = $query->get();
            foreach ($resources as $resource) {
                $resource->huawei_entry_detail->huawei_material->name = HuaweiUtils::sanitizeText2($resource->huawei_entry_detail->huawei_material->name);
            }
            $materials = HuaweiMaterial::where('prefix', $found_project->prefix)
                ->get()
                ->makeHidden(['huawei_entry_details'])
                ->filter(function ($material) {
                    return $material->available_quantity > 0;
                })
                ->map(function ($material) {
                    $material->name = HuaweiUtils::sanitizeText2($material->name); // Aplica la función de sanitización al nombre
                    return $material;
                })
                ->values()
                ->toArray();

            return Inertia::render('Huawei/Projects/Resources/Resources', [
                'resources' => $resources,
                'equipment' => $equipment,
                'materials' => $materials,
                'huawei_project' => $huawei_project,
                'huawei_project_name_code' => $huawei_project_name_code,
                'project_state' => $project_state,
                'search' => $request
            ]);
        }
    }

    public function storeProjectResource($huawei_project, Request $request, $equipment = null)
    {
        $found_project = HuaweiProject::find($huawei_project);

        if (!$found_project->status) {
            abort(403, 'Acción no permitida');
        }

        if ($equipment) {

            $request->validate([
                'huawei_entry_detail_ids' => 'required',
            ]);

            DB::beginTransaction();

            foreach ($request->huawei_entry_detail_ids as $detail) {
                $foundDetail = HuaweiEntryDetail::find($detail);
                if ($foundDetail->assigned_diu !== $found_project->assigned_diu) {
                    DB::rollBack();
                    abort(403, 'Acción no permitida.');
                }
                HuaweiProjectResource::create([
                    'huawei_project_id' => $huawei_project,
                    'huawei_entry_detail_id' => $detail,
                    'quantity' => 1,
                    'output_date' => $request->output_date
                ]);
            }

            DB::commit();
        } else {
            $entry_detail = HuaweiEntryDetail::find($request->huawei_entry_detail_id);

            $request->validate([
                'huawei_entry_detail_id' => 'required',
                'quantity' => [
                    'required',
                    function ($attribute, $value, $fail) use ($entry_detail) {
                        if ($value !== null && $value > $entry_detail->available_quantity) {
                            $fail('La cantidad debe ser menor o igual a la cantidad disponible del recurso.');
                        }
                    },
                ],
                'output_date' => 'required'
            ]);

            HuaweiProjectResource::create([
                'huawei_project_id' => $huawei_project,
                'huawei_entry_detail_id' => $request->huawei_entry_detail_id,
                'quantity' => $request->quantity,
                'output_date' => $request->output_date
            ]);
        }

        return redirect()->back();
    }

    public function refundResource(HuaweiProjectResource $huawei_resource, Request $request, $equipment = null)
    {
        $found_project = HuaweiProject::find($huawei_resource->huawei_project_id);

        if (!$found_project->status) {
            abort(403, 'Acción no permitida');
        }

        $request->validate([
            'quantity' => [
                'nullable',
                function ($attribute, $value, $fail) use ($huawei_resource) {
                    if ($value !== null && $value > $huawei_resource->quantity) {
                        $fail('La cantidad debe ser menor o igual a la cantidad asignada del recurso.');
                    }
                },
            ],
        ]);

        if ($equipment) {
            $huawei_resource->update([
                'quantity' => 0
            ]);
        } else {
            $huawei_resource->update([
                'quantity' => $huawei_resource->quantity - $request->quantity
            ]);
        }

        return redirect()->back();
    }
}
