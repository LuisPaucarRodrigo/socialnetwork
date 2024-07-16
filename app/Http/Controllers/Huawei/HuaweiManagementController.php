<?php

namespace App\Http\Controllers\Huawei;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\BrandModel;
use App\Models\HuaweiEntry;
use App\Models\HuaweiEquipment;
use App\Models\HuaweiEntryDetail;
use App\Models\HuaweiEquipmentSerie;
use App\Models\HuaweiMaterial;
use App\Models\Brand;
use App\Models\HuaweiRefund;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;

class HuaweiManagementController extends Controller
{
    public function show ($equipment = null)
    {
        if ($equipment){
            return Inertia::render('Huawei/Materials', [
                'equipments' => HuaweiEquipment::with('huawei_equipment_series', 'brand_model.brand')->paginate(10),
                'brand_models' => BrandModel::all(),
                'brands' => Brand::all(),
                'equipment' => $equipment
            ]);
        } else {
            return Inertia::render('Huawei/Materials', [
                'materials' => HuaweiMaterial::with('brand_model.brand')->paginate(10),
                'brand_models' => BrandModel::all(),
                'brands' => Brand::all(),
                'equipment' => $equipment
            ]);
        }
    }

    public function searchIndex($request, $equipment = null)
    {
        $searchTerm = strtolower($request);

        if ($equipment) {
            // Consulta para equipos
            $equipmentsQuery = HuaweiEquipment::query();

            // Aplicar filtros de búsqueda para equipos
            $equipmentsQuery->where(function ($query) use ($searchTerm) {
                $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(claro_code) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereHas('brand_model.brand', function ($query) use ($searchTerm) {
                        $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"]);
                    })
                    ->orWhereHas('brand_model', function ($query) use ($searchTerm) {
                        $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"]);
                    });
            });

            // Obtener los resultados de equipos
            $equipments = $equipmentsQuery->with('huawei_equipment_series', 'brand_model.brand')->get();

            // Retornar la vista para equipos
            return Inertia::render('Huawei/Materials', [
                'equipments' => $equipments,
                'brand_models' => BrandModel::all(),
                'brands' => Brand::all(),
                'equipment' => $equipment,
                'search' => $request,
            ]);
        } else {
            $materialsQuery = HuaweiMaterial::query();

            // Aplicar filtros de búsqueda para materiales
            $materialsQuery->where(function ($query) use ($searchTerm) {
                $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(claro_code) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereHas('brand_model.brand', function ($query) use ($searchTerm) {
                        $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"]);
                    })
                    ->orWhereHas('brand_model', function ($query) use ($searchTerm) {
                        $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"]);
                    });
            });

            // Obtener los resultados de materiales
            $materials = $materialsQuery->with('brand_model.brand')->get();

            // Retornar la vista para materiales
            return Inertia::render('Huawei/Materials', [
                'materials' => $materials,
                'brand_models' => BrandModel::all(),
                'brands' => Brand::all(),
                'equipment' => $equipment,
                'search' => $request,
            ]);
        }
    }


    public function create ()
    {
        return Inertia::render('Huawei/InventoryStore', [
            'brand_models' => BrandModel::all(),
            'equipments' => HuaweiEquipment::with('brand_model', 'huawei_equipment_series')->get(),
            'materials' => HuaweiMaterial::with('brand_model')->get(),
            'brands' => Brand::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'guide_number' => 'required',
            'entry_date' => 'required',
            'observation' => 'nullable',
            'materials' => 'nullable|array',
            'equipments' => 'nullable|array'
        ]);

        // Verificar si ambos materials y equipments están vacíos
        if (empty($request->materials) && empty($request->equipments)) {
            return back()->withErrors(['error_empty' => 'Debe agregar al menos un material o equipo.'])->withInput();
        }

        $huawei_entry = HuaweiEntry::create([
            'guide_number' => $request->guide_number,
            'entry_date' => $request->entry_date,
            'observation' => $request->observation
        ]);

        if ($request->materials) {
            foreach ($request->materials as $material) {
                if (isset($material['material_id']) && $material['material_id']) {
                    $huawei_entry_detail = HuaweiEntryDetail::create([
                        'huawei_entry_id' => $huawei_entry->id,
                        'huawei_material_id' => $material['material_id'], // Asegúrate de usar 'huawei_material_id' aquí
                        'quantity' => $material['quantity'],
                        'unit_price' => $material['unit_price']
                    ]);
                } else {
                    $new_material = HuaweiMaterial::create([
                        'name' => $material['name'],
                        'claro_code' => $material['claro_code'],
                        'model_id' => $material['brand_model']
                    ]);

                    $huawei_entry_detail = HuaweiEntryDetail::create([
                        'huawei_entry_id' => $huawei_entry->id,
                        'huawei_material_id' => $new_material->id, // Usar el ID del nuevo material creado
                        'quantity' => $material['quantity'],
                        'unit_price' => $material['unit_price']
                    ]);
                }
            }
        }

        if ($request->equipments) {
            foreach ($request->equipments as $equipment) {
                if (isset($equipment['equipment_id']) && $equipment['equipment_id']) {
                    foreach ($equipment['series'] as $serie) {
                        $new_serie = HuaweiEquipmentSerie::create([
                            'huawei_equipment_id' => $equipment['equipment_id'], // Asegúrate de usar 'huawei_equipment_id' aquí
                            'serie_number' => $serie
                        ]);

                        $huawei_entry_detail = HuaweiEntryDetail::create([
                            'huawei_entry_id' => $huawei_entry->id,
                            'huawei_equipment_serie_id' => $new_serie->id,
                            'quantity' => 1,
                            'unit_price' => $equipment['unit_price']
                        ]);
                    }
                } else {
                    $new_equipment = HuaweiEquipment::create([
                        'name' => $equipment['name'],
                        'claro_code' => $equipment['claro_code'],
                        'model_id' => $equipment['brand_model']
                    ]);

                    foreach ($equipment['series'] as $serie) {
                        $new_serie = HuaweiEquipmentSerie::create([
                            'huawei_equipment_id' => $new_equipment->id, // Usar el ID del nuevo equipo creado
                            'serie_number' => $serie
                        ]);

                        $huawei_entry_detail = HuaweiEntryDetail::create([
                            'huawei_entry_id' => $huawei_entry->id,
                            'huawei_equipment_serie_id' => $new_serie->id,
                            'quantity' => 1,
                            'unit_price' => $equipment['unit_price']
                        ]);
                    }
                }
            }
        }

    }



    public function storeBrand (Request $request)
    {
        $data = $request->validate([
            'name' => ['required', Rule::unique('brands')],
        ]);

        $new = Brand::create($data);

        return response()->json(['new'=> $new],200);
    }

    public function storeBrandModel (Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'brand_id' => 'required'
        ]);

        $new = BrandModel::create($data);

        return response()->json(['new'=> $new],200);
    }

    public function showDetails($id, $equipment = null)
    {
        if ($equipment) {
            $entries = HuaweiEntryDetail::whereHas('huawei_equipment_serie', function ($query) use ($id) {
                $query->whereHas('huawei_equipment', function ($query) use ($id) {
                    $query->where('id', $id);
                });
            })
            ->with('huawei_entry', 'huawei_equipment_serie.huawei_equipment', 'latest_huawei_project_resource.huawei_project')
            ->paginate(10);
        } else {
            $entries = HuaweiEntryDetail::where('huawei_material_id', $id)->with('huawei_entry', 'huawei_material')->paginate(10);
        }

        return Inertia::render('Huawei/Details', [
            'entries' => $entries,
            'equipment' => $equipment,
            'id' => $id
        ]);
    }

    public function search($id, $request, $equipment = null)
    {
        $searchTerm = strtolower($request);

        // Consulta principal
        $query = HuaweiEntryDetail::query();

        if ($equipment) {
            // Consultas específicas para equipos
            $equipmentSerieQuery = HuaweiEntryDetail::query()
                ->whereHas('huawei_equipment_serie', function ($query) use ($searchTerm, $id) {
                    $query->where('huawei_equipment_id', $id)
                          ->whereRaw('LOWER(serie_number) LIKE ?', ["%{$searchTerm}%"]);
                })->whereNull('huawei_material_id')
                ->with('huawei_entry', 'huawei_equipment_serie.huawei_equipment', 'latest_huawei_project_resource.huawei_project')
                ->get();

            $guideNumberQuery = HuaweiEntryDetail::query()
                ->whereHas('huawei_entry', function ($query) use ($searchTerm) {
                    $query->whereRaw('LOWER(guide_number) LIKE ?', ["%{$searchTerm}%"]);
                })->whereNull('huawei_material_id')
                ->with('huawei_entry', 'huawei_equipment_serie.huawei_equipment', 'latest_huawei_project_resource.huawei_project')
                ->get();

            $projectQuery = HuaweiEntryDetail::query()
                ->whereHas('huawei_project_resources', function ($query) use ($searchTerm) {
                    $query->whereHas('huawei_project', function ($query) use ($searchTerm) {
                        $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"]);
                    })
                    ->orWhereHas('huawei_project', function ($query) use ($searchTerm) {
                        $query->whereRaw('LOWER(ot) LIKE ?', ["%{$searchTerm}%"]);
                    });
                })->whereNull('huawei_material_id')
                ->with('huawei_entry', 'huawei_equipment_serie.huawei_equipment', 'latest_huawei_project_resource.huawei_project')
                ->get();

            $queryByCode = HuaweiEntryDetail::query()
                ->whereNull('huawei_material_id')
                ->with(['huawei_entry', 'huawei_equipment_serie.huawei_equipment', 'latest_huawei_project_resource.huawei_project'])
                ->get()
                ->filter(function ($detail) use ($searchTerm) {
                    if ($detail->latest_huawei_project_resource &&
                        $detail->latest_huawei_project_resource->huawei_project &&
                        str_contains(strtolower($detail->latest_huawei_project_resource->huawei_project->code), $searchTerm)) {
                        return true;
                    }
                    return false;
                });

            // Fusionar todas las colecciones de resultados en una sola y eliminar duplicados
            $mergedResults = collect()
                ->merge($equipmentSerieQuery)
                ->merge($guideNumberQuery)
                ->merge($projectQuery)
                ->merge($queryByCode)
                ->unique('id')
                ->values(); // Asegurarse de que las claves sean secuenciales

        } else {
            // Si no es equipo, buscar por material y número de guía
            $query->whereHas('huawei_material', function ($query) use ($id) {
                $query->where('id', $id);
            });

            $query->whereHas('huawei_entry', function ($query) use ($searchTerm) {
                $query->whereRaw('LOWER(guide_number) LIKE ?', ["%{$searchTerm}%"]);
            });

            // Obtener los resultados finales para no equipos
            $mergedResults = $query->distinct()
                ->with('huawei_entry', 'huawei_material',)
                ->get();
        }

        // Retornar los resultados fusionados
        return Inertia::render('Huawei/Details', [
            'entries' => $mergedResults,
            'equipment' => $equipment,
            'id' => $id,
            'search' => $request
        ]);
    }

    public function refund(Request $request, $equipment = null)
    {
        $huawei_entry_detail_founded = HuaweiEntryDetail::find($request->huawei_entry_detail_id);

        if ($huawei_entry_detail_founded->state !== 'Disponible'){
            abort(403, 'Acción no permitida');
        }

        $request->validate([
            'huawei_entry_detail_id' => 'required',
            'quantity' => 'nullable',
            'observation' => 'nullable'
        ]);

        // Encontrar el detalle de entrada por ID
        $entryDetail = HuaweiEntryDetail::findOrFail($request->huawei_entry_detail_id);

        // Obtener la cantidad disponible usando el campo calculado
        $availableQuantity = $entryDetail->available_quantity;

        // Si es equipo, la cantidad siempre es 1
        if ($equipment) {
            if ($availableQuantity < 1) {
                return redirect()->back()->withErrors(['error_exceed' => 'No hay suficiente cantidad disponible para la devolución.']);
            }
            HuaweiRefund::create([
                'huawei_entry_detail_id' => $request->huawei_entry_detail_id,
                'quantity' => 1,
                'observation' => $request->observation
            ]);
        } else {
            // Verificar si la cantidad solicitada es menor o igual a la cantidad disponible
            if ($request->quantity > $availableQuantity) {
                return redirect()->back()->withErrors(['error_exceed' => 'La cantidad solicitada excede la cantidad disponible.']);
            }
            HuaweiRefund::create([
                'huawei_entry_detail_id' => $request->huawei_entry_detail_id,
                'quantity' => $request->quantity,
                'observation' => $request->observation
            ]);
        }

        return redirect()->back();
    }

    public function getRefunds ($equipment = null)
    {
        if ($equipment){
            $refunds = HuaweiRefund::with('huawei_entry_detail.huawei_equipment_serie.huawei_equipment', 'huawei_entry_detail.huawei_entry')
                ->whereHas('huawei_entry_detail', function ($query) {
                    $query->whereNull('huawei_material_id');
                })
                ->paginate(10);
        }else{
            $refunds = HuaweiRefund::with('huawei_entry_detail.huawei_material', 'huawei_entry_detail.huawei_entry')
                ->whereHas('huawei_entry_detail', function ($query) {
                    $query->whereNull('huawei_equipment_serie_id');
                })
                ->paginate(10);
        }
        return Inertia::render('Huawei/Refunds', [
            'refunds' => $refunds,
            'equipment' => $equipment
        ]);
    }

    public function searchRefunds ($request, $equipment = null)
    {
        $searchTerm = strtolower($request);

        $query = HuaweiRefund::query();

        if ($equipment){
            $query->whereHas('huawei_entry_detail', function ($query){
                $query->whereNull('huawei_material_id');
            });
            $query->where(function ($query) use ($searchTerm) {
                $query->whereHas('huawei_entry_detail.huawei_equipment_serie.huawei_equipment', function ($query) use ($searchTerm) {
                    $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"]);
                })
                ->orWhereHas('huawei_entry_detail.huawei_equipment_serie', function ($query) use ($searchTerm) {
                    $query->whereRaw('LOWER(serie_number) LIKE ?', ["%{$searchTerm}%"]);
                });
            });
        }else{
            $query->whereHas('huawei_entry_detail', function ($query) {
                $query->whereNull('huawei_equipment_serie_id');
            });
            $query->whereHas('huawei_entry_detail.huawei_material', function ($query) use ($searchTerm) {
                $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"]);
            });
        }

        $refunds = $query->with('huawei_entry_detail.huawei_equipment_serie.huawei_equipment', 'huawei_entry_detail.huawei_material', 'huawei_entry_detail.huawei_entry')->get();

        return Inertia::render('Huawei/Refunds', [
            'refunds' => $refunds,
            'equipment' => $equipment,
            'search' => $request
        ]);
    }

}
