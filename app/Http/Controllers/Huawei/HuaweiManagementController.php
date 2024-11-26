<?php

namespace App\Http\Controllers\Huawei;

use App\Exports\HuaweiInventoryExport;
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
use App\Models\HuaweiPendingOrder;
use App\Models\HuaweiRefund;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use App\Models\HuaweiSpecialRefund;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class HuaweiManagementController extends Controller
{
    public function show ($warehouse, $equipment = null)
    {
        $prefix = null;

        switch ($warehouse){
            case '1':
                $prefix = 'Claro';
                break;
            case '2':
                $prefix = 'Entel';
                break;
            default:
                $prefix = null;
                abort(403, 'Acción no permitida');
        }

        if ($equipment){
            $equipments = HuaweiEquipment::with('huawei_equipment_series', 'brand_model.brand')->where('prefix', $prefix)->paginate(10);
            foreach ($equipments as $item) {
                $item->name = $this->sanitizeText2($item->name);
            }

            return Inertia::render('Huawei/Materials', [
                'equipments' => $equipments,
                'brand_models' => BrandModel::all(),
                'brands' => Brand::all(),
                'equipment' => $equipment,
                'warehouse' => $warehouse
            ]);
        } else {
            $materials = HuaweiMaterial::with('brand_model.brand')->where('prefix', $prefix)->paginate(10);
            foreach ($materials as $material) {
                $material->name = $this->sanitizeText2($material->name);
            }
            return Inertia::render('Huawei/Materials', [
                'materials' => $materials,
                'brand_models' => BrandModel::all(),
                'brands' => Brand::all(),
                'equipment' => $equipment,
                'warehouse' => $warehouse
            ]);
        }
    }

    public function searchIndex($warehouse, $request, $equipment = null)
    {
        $prefix = null;

        switch ($warehouse){
            case '1':
                $prefix = 'Claro';
                break;
            case '2':
                $prefix = 'Entel';
                break;
            default:
                $prefix = null;
                abort(403, 'Acción no permitida');
        }

        $searchTerm = strtolower($request);

        if ($equipment) {
            // Consulta para equipos
            $equipmentsQuery = HuaweiEquipment::query();

            // Aplicar filtros de búsqueda para equipos
            $equipmentsQuery->where('prefix', $prefix)->where(function ($query) use ($searchTerm) {
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
            $equipments = $equipmentsQuery->with('huawei_equipment_series', 'brand_model.brand')->get()
                ->map(function ($equipment){
                    $equipment->name = $this->sanitizeText2($equipment->name);
                    return $equipment;
                });

            // Retornar la vista para equipos
            return Inertia::render('Huawei/Materials', [
                'equipments' => $equipments,
                'brand_models' => BrandModel::all(),
                'brands' => Brand::all(),
                'equipment' => $equipment,
                'search' => $request,
                'warehouse' => $warehouse
            ]);
        } else {
            $materialsQuery = HuaweiMaterial::query();

            // Aplicar filtros de búsqueda para materiales
            $materialsQuery->where('prefix', $prefix)->where(function ($query) use ($searchTerm) {
                $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(claro_code) LIKE ?', ["%{$searchTerm}%"]);
            });

            // Obtener los resultados de materiales
            $materials = $materialsQuery->with('brand_model.brand')->get()
                ->map(function ($material){
                    $material->name = $this->sanitizeText2($material->name);
                    return $material;
                });

            // Retornar la vista para materiales
            return Inertia::render('Huawei/Materials', [
                'materials' => $materials,
                'brand_models' => BrandModel::all(),
                'brands' => Brand::all(),
                'equipment' => $equipment,
                'search' => $request,
                'warehouse' => $warehouse
            ]);
        }
    }


    public function create ()
    {
        return Inertia::render('Huawei/InventoryStore', [
            'brand_models' => BrandModel::all(),
            'brands' => Brand::all(),
        ]);
    }

    public function getInventoryPerWarehouse ($value)
    {
        $materials = null;
        $equipments = null;
        switch ($value){
            case '1':
                $materials = HuaweiMaterial::select('id','name', 'model_id', 'prefix', 'claro_code', 'unit')->where('prefix', 'Claro')->with('brand_model:id,name,brand_id')->get();
                $equipments = HuaweiEquipment::select('id','name', 'model_id', 'prefix', 'claro_code', 'unit')->where('prefix', 'Claro')->with('brand_model:id,name,brand_id')->get();
                break;

            case '2':
                $materials = HuaweiMaterial::select('id','name', 'model_id', 'prefix', 'claro_code', 'unit')->where('prefix', 'Entel')->with('brand_model:id,name,brand_id')->get();
                $equipments = HuaweiEquipment::select('id','name', 'model_id', 'prefix', 'claro_code', 'unit')->where('prefix', 'Entel')->with('brand_model:id,name,brand_id')->get();
                break;

            default:
                abort(403, 'Acción no permitida');
        }

        $materials = $materials->makeHidden(['huawei_entry_details', 'quantity', 'available_quantity'])->transform(function ($material) {
            $material->name = $this->sanitizeText2($material->name);
            return $material;
        });

        $equipments = $equipments->makeHidden(['quantity', 'available_quantity'])->transform(function ($equipment) {
            $equipment->name = $this->sanitizeText2($equipment->name);
            return $equipment;
        });

        return response()->json(['materials' => $materials, 'equipments' => $equipments]);
    }

    private function sanitizeText2($text) {
        // Usa una expresión regular para eliminar el texto entre paréntesis al principio del texto
        return preg_replace('/^\(.*?\)\s*/', '', $text);
    }

    // public function antiquation ()
    // {
    //     return Inertia::render('Huawei/Antiquation', [
    //         'equipments' => HuaweiEquipment::with('huawei_equipment_series')->get()
    //     ]);
    // }

    public function store(Request $request)
    {
        $request->validate([
            'guide_number' => 'nullable|unique:huawei_entries,guide_number',
            'entry_date' => 'nullable',
            'observation' => 'nullable',
            'order_number' => 'required',
            'order_date' => 'required',
            'materials' => 'nullable|array',
            'equipments' => 'nullable|array',
            'warehouse' => 'required'
        ]);

        // Verificar si ambos materials y equipments están vacíos
        if (empty($request->materials) && empty($request->equipments)) {
            return back()->withErrors(['error_empty' => 'Debe agregar al menos un material o equipo.'])->withInput();
        }

        DB::beginTransaction(); // Iniciar transacción

        // Crear entrada
        $huawei_entry = HuaweiEntry::create([
            'guide_number' => $request->guide_number,
            'entry_date' => $request->entry_date,
            'observation' => $request->observation
        ]);

        $warehouse = null;

        switch ($request->warehouse){
            case '1':
                $warehouse = 'Claro';
                break;
            case '2':
                $warehouse = 'Entel';
                break;
            default:
                abort(403, 'Acción no permitida');
        }

        try {
            // Manejar materiales
            if ($request->materials) {
                foreach ($request->materials as $material) {
                    if (!$material['order_number'] && !$request->order_number){
                        DB::rollBack();
                        abort(403, 'Acción no permitida.');
                    }
                    if (!$material['order_date'] && !$request->order_date){
                        DB::rollBack();
                        abort(403, 'Acción no permitida.');
                    }
                    if (isset($material['material_id']) && $material['material_id']) {
                        HuaweiEntryDetail::create([
                            'huawei_entry_id' => $huawei_entry->id,
                            'huawei_material_id' => $material['material_id'],
                            'quantity' => $material['quantity'],
                            'unit_price' => $material['unit_price'],
                            'observation' => $material['observation'],
                            'order_number' => $material['order_number'],
                            'order_date' => $material['order_date'],
                        ]);
                    } else {
                        $new_material = HuaweiMaterial::create([
                            'name' => '(' . $warehouse . ') ' . $material['name'],
                            'claro_code' => $material['claro_code'],
                            'unit' => $material['unit'],
                            'prefix' => $warehouse
                        ]);

                        HuaweiEntryDetail::create([
                            'huawei_entry_id' => $huawei_entry->id,
                            'huawei_material_id' => $new_material->id,
                            'quantity' => $material['quantity'],
                            'unit_price' => $material['unit_price'],
                            'observation' => $material['observation'],
                            'order_number' => $material['order_number'],
                            'order_date' => $material['order_date'],
                        ]);
                    }
                }
            }

            // Manejar equipos
            if ($request->equipments) {
                if (!$request->guide_number || !$request->entry_date) {
                    abort(403, 'Los equipos deben entrar con guía de remisión');
                }

                foreach ($request->equipments as $equipment) {
                        if (isset($equipment['equipment_id']) && $equipment['equipment_id']) {
                            foreach ($equipment['series'] as $serie) {
                                $existing_serie = HuaweiEquipmentSerie::where('huawei_equipment_id', $equipment['equipment_id'])
                                    ->where('serie_number', $serie)
                                    ->first();

                                if ($existing_serie){
                                    DB::rollBack();
                                    return response()->json(['error' => 'Ocurrió un error durante la inserción de datos o se encontraron duplicados'], 500);
                                }

                                if (!$serie['order_number'] && !$request->order_number){
                                    DB::rollBack();
                                    abort(403, 'Acción no permitida.');
                                }

                                if (!$serie['order_date'] && !$request->order_date){
                                    DB::rollBack();
                                    abort(403, 'Acción no permitida.');
                                }

                                $new_serie = HuaweiEquipmentSerie::create([
                                    'huawei_equipment_id' => $equipment['equipment_id'], // Asegúrate de usar 'huawei_equipment_id' aquí
                                    'serie_number' => $serie['serie']
                                ]);

                                $huawei_entry_detail = HuaweiEntryDetail::create([
                                    'huawei_entry_id' => $huawei_entry->id,
                                    'huawei_equipment_serie_id' => $new_serie->id,
                                    'quantity' => 1,
                                    'unit_price' => $serie['unit_price'],
                                    'assigned_diu' => $serie['assigned_diu'],
                                    'observation' => $serie['observation'],
                                    'order_number' => $serie['order_number'],
                                    'order_date' => $serie['order_date']
                                ]);
                            }
                        } else {
                            // Crear nuevo equipo
                            $new_equipment = HuaweiEquipment::create([
                                'name' => '(' . $warehouse . ') ' . $equipment['name'],
                                'claro_code' => $equipment['claro_code'],
                                'model_id' => $equipment['brand_model'],
                                'prefix' => $warehouse,
                                'unit' => 'Unidad'
                            ]);

                            foreach ($equipment['series'] as $serie) {
                                if (!$serie['order_number'] && !$request->order_number){
                                    DB::rollBack();
                                    abort(403, 'Acción no permitida.');
                                }

                                $new_serie = HuaweiEquipmentSerie::create([
                                    'huawei_equipment_id' => $new_equipment->id, // Usar el ID del nuevo equipo creado
                                    'serie_number' => $serie['serie']
                                ]);

                                $huawei_entry_detail = HuaweiEntryDetail::create([
                                    'huawei_entry_id' => $huawei_entry->id,
                                    'huawei_equipment_serie_id' => $new_serie->id,
                                    'quantity' => 1,
                                    'unit_price' => $serie['unit_price'],
                                    'assigned_diu' => $serie['assigned_diu'],
                                    'observation' => $serie['observation'],
                                    'order_number' => $serie['order_number'],
                                    'order_date' => $serie['order_date']
                                ]);
                            }
                        }
                }
            }

            DB::commit(); // Confirmar la transacción si todo va bien

        } catch (Throwable $e) {
            DB::rollBack(); // Revertir transacción en caso de error
            return response()->json(['error' => $e], 500);
        }
    }

    public function storeOrder (Request $request)
    {
        $request->validate([
            'order_number' => 'required',
            'order_date' => 'required',
            'materials' => 'nullable|array',
            'equipments' => 'nullable|array',
            'warehouse' => 'required',
            'observation' => 'nullable'
        ]);

        if (empty($request->materials) && empty($request->equipments)) {
            return back()->withErrors(['error_empty' => 'Debe agregar al menos un material o equipo.'])->withInput();
        }

        DB::beginTransaction();

        $huawei_pending_order = HuaweiPendingOrder::create([
            'order_number' => $request->order_number,
            'order_date' => $request->order_date,
            'observation' => $request->observation
        ]);

        $warehouse = null;

        switch ($request->warehouse){
            case '1':
                $warehouse = 'Claro';
                break;
            case '2':
                $warehouse = 'Entel';
                break;
            default:
                abort(403, 'Acción no permitida');
        }

        try {
            // Manejar materiales
            if ($request->materials) {
                foreach ($request->materials as $material) {
                    if (!$material['order_number'] && !$request->order_number){
                        DB::rollBack();
                        abort(403, 'Acción no permitida.');
                    }
                    if (!$material['order_date'] && !$request->order_date){
                        DB::rollBack();
                        abort(403, 'Acción no permitida.');
                    }
                    if (isset($material['material_id']) && $material['material_id']) {
                        HuaweiEntryDetail::create([
                            'huawei_order_id' => $huawei_pending_order->id,
                            'huawei_material_id' => $material['material_id'],
                            'quantity' => $material['quantity'],
                            'unit_price' => $material['unit_price'],
                            'observation' => $material['observation'],
                            'order_number' => $material['order_number'],
                            'order_date' => $material['order_date'],
                        ]);
                    } else {
                        $new_material = HuaweiMaterial::create([
                            'name' => '(' . $warehouse . ') ' . $material['name'],
                            'claro_code' => $material['claro_code'],
                            'unit' => $material['unit'],
                            'prefix' => $warehouse
                        ]);

                        HuaweiEntryDetail::create([
                            'huawei_order_id' => $huawei_pending_order->id,
                            'huawei_material_id' => $new_material->id,
                            'quantity' => $material['quantity'],
                            'unit_price' => $material['unit_price'],
                            'observation' => $material['observation'],
                            'order_number' => $material['order_number'],
                            'order_date' => $material['order_date'],
                        ]);
                    }
                }
            }

            // Manejar equipos
            if ($request->equipments) {
                foreach ($request->equipments as $equipment) {
                        if (isset($equipment['equipment_id']) && $equipment['equipment_id']) {
                            foreach ($equipment['series'] as $serie) {
                                $existing_serie = HuaweiEquipmentSerie::where('huawei_equipment_id', $equipment['equipment_id'])
                                    ->where('serie_number', $serie)
                                    ->first();

                                if ($existing_serie){
                                    DB::rollBack();
                                    return response()->json(['error' => 'Ocurrió un error durante la inserción de datos o se encontraron duplicados'], 500);
                                }

                                if (!$serie['order_number'] && !$request->order_number){
                                    DB::rollBack();
                                    abort(403, 'Acción no permitida.');
                                }

                                if (!$serie['order_date'] && !$request->order_date){
                                    DB::rollBack();
                                    abort(403, 'Acción no permitida.');
                                }

                                $new_serie = HuaweiEquipmentSerie::create([
                                    'huawei_equipment_id' => $equipment['equipment_id'], // Asegúrate de usar 'huawei_equipment_id' aquí
                                    'serie_number' => $serie['serie']
                                ]);

                                $huawei_entry_detail = HuaweiEntryDetail::create([
                                    'huawei_order_id' => $huawei_pending_order->id,
                                    'huawei_equipment_serie_id' => $new_serie->id,
                                    'quantity' => 1,
                                    'unit_price' => $serie['unit_price'],
                                    'assigned_diu' => $serie['assigned_diu'],
                                    'observation' => $serie['observation'],
                                    'order_number' => $serie['order_number'],
                                    'order_date' => $serie['order_date']
                                ]);
                            }
                        } else {
                            // Crear nuevo equipo
                            $new_equipment = HuaweiEquipment::create([
                                'name' => '(' . $warehouse . ') ' . $equipment['name'],
                                'claro_code' => $equipment['claro_code'],
                                'model_id' => $equipment['brand_model'],
                                'prefix' => $warehouse,
                                'unit' => 'Unidad'
                            ]);

                            foreach ($equipment['series'] as $serie) {
                                if (!$serie['order_number'] && !$request->order_number){
                                    DB::rollBack();
                                    abort(403, 'Acción no permitida.');
                                }

                                $new_serie = HuaweiEquipmentSerie::create([
                                    'huawei_equipment_id' => $new_equipment->id, // Usar el ID del nuevo equipo creado
                                    'serie_number' => $serie['serie']
                                ]);

                                $huawei_entry_detail = HuaweiEntryDetail::create([
                                    'huawei_order_id' => $huawei_pending_order->id,
                                    'huawei_equipment_serie_id' => $new_serie->id,
                                    'quantity' => 1,
                                    'unit_price' => $serie['unit_price'],
                                    'assigned_diu' => $serie['assigned_diu'],
                                    'observation' => $serie['observation'],
                                    'order_number' => $serie['order_number'],
                                    'order_date' => $serie['order_date']
                                ]);
                            }
                        }
                }
            }

            DB::commit(); // Confirmar la transacción si todo va bien

        } catch (Throwable $e) {
            DB::rollBack(); // Revertir transacción en caso de error
            return response()->json(['error' => $e], 500);
        }
    }

    public function verifySerie(Request $request, HuaweiEquipment $equipment)
    {
        $existingSeries = $equipment->huawei_equipment_series->pluck('serie_number')->toArray();

        if (in_array($request->input('serie_number'), $existingSeries)) {
            return response()->json(['message' => 'found'], 200);
        }

        return response()->json(['message' => 'notfound'], 200);
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
            ->with('huawei_entry', 'huawei_pending_order', 'huawei_equipment_serie.huawei_equipment', 'latest_huawei_project_resource.huawei_project')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

            foreach ($entries as $entry){
                $entry->huawei_equipment_serie->huawei_equipment->name = $this->sanitizeText2($entry->huawei_equipment_serie->huawei_equipment->name);
            }
        } else {
            $entries = HuaweiEntryDetail::where('huawei_material_id', $id)->with('huawei_entry', 'huawei_pending_order', 'huawei_material', 'huawei_project_resources.huawei_project', 'huawei_project_resources.huawei_project_liquidation')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

            foreach ($entries as $entry){
                $entry->huawei_material->name = $this->sanitizeText2($entry->huawei_material->name);
            }
        }

        return Inertia::render('Huawei/Details', [
            'entries' => $entries,
            'equipment' => $equipment,
            'id' => $id
        ]);
    }

    public function getGeneralEquipments($prefix)
    {
        $equipments = HuaweiEntryDetail::whereNull('huawei_material_id')
        ->whereHas('huawei_equipment_serie.huawei_equipment', function ($query) use ($prefix) {
            $query->where('prefix', $prefix);
        })
        ->with([
            'huawei_entry',
            'huawei_equipment_serie.huawei_equipment',  // Relación ya filtrada
            'latest_huawei_project_resource.huawei_project'
        ])
        ->orderBy('created_at', 'desc')
        ->paginate(20);

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
            $equipment->huawei_equipment_serie->huawei_equipment->name = $this->sanitizeText2($equipment->huawei_equipment_serie->huawei_equipment->name);
        }

        return Inertia::render('Huawei/GeneralEquipments', [
            'equipments' => $equipments,
            'warehouse' => $prefix,
            'data' => $data
        ]);
    }

    public function searchGeneralEquipments($prefix, $request)
    {
        $searchTerm = strtolower($request);

        // Consulta principal, incluyendo filtro de `prefix` y campos de búsqueda especificados
        $equipments = HuaweiEntryDetail::whereNull('huawei_material_id')
            ->whereHas('huawei_equipment_serie.huawei_equipment', function ($query) use ($prefix) {
                $query->where('prefix', $prefix);
            })
            ->where(function ($query) use ($searchTerm) {
                // Campos de búsqueda específicos
                $query->whereRaw('LOWER(assigned_diu) LIKE ?', ["%{$searchTerm}%"])
                      ->orWhereRaw('LOWER(new_site) LIKE ?', ["%{$searchTerm}%"])
                      ->orWhereRaw('LOWER(order_number) LIKE ?', ["%{$searchTerm}%"])
                      ->orWhereRaw('LOWER(observation) LIKE ?', ["%{$searchTerm}%"])
                      ->orWhereHas('huawei_entry', function ($subQuery) use ($searchTerm){
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
                'huawei_equipment_serie.huawei_equipment',
                'latest_huawei_project_resource.huawei_project'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Consulta adicional para los filtros por campos calculados
        $equipmentsToFilter = HuaweiEntryDetail::whereNull('huawei_material_id')
            ->whereHas('huawei_equipment_serie.huawei_equipment', function ($query) use ($prefix) {
                $query->where('prefix', $prefix);
            })
            ->with([
                'huawei_entry',
                'huawei_equipment_serie.huawei_equipment',
                'latest_huawei_project_resource.huawei_project'
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
                $equipment->huawei_equipment_serie->huawei_equipment->name = $this->sanitizeText2($equipment->huawei_equipment_serie->huawei_equipment->name);
            }
        }

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

        return Inertia::render('Huawei/GeneralEquipments', [
            'equipments' => $finalEquipments,
            'search' => $request,
            'warehouse' => $prefix,
            'data' => $data
        ]);
    }

    public function searchGeneralAdvance($prefix, Request $request)
    {
        // Construimos la consulta base
        $query = HuaweiEntryDetail::whereNull('huawei_material_id')
            ->whereHas('huawei_equipment_serie.huawei_equipment', function ($query) use ($prefix) {
                $query->where('prefix', $prefix);
            })
            ->with([
                'huawei_entry',
                'huawei_equipment_serie.huawei_equipment',
                'latest_huawei_project_resource.huawei_project'
            ]);

        // Contar los elementos en bruto
        $equipmentsForCounts = $query->get();
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

        if (count($request->selectedGuideNumbers) < $data['guide_numbers']){
            $guide_numbers = $request->selectedGuideNumbers;
            $query->whereHas('huawei_entry', function ($subQuery) use ($guide_numbers){
                $subQuery->whereIn('guide_number', $guide_numbers);
            });
        }

        if (count($request->selectedSAPCodes) < $data['sap_codes']){
            $sap_codes = $request->selectedSAPCodes;
            $query->whereHas('huawei_equipment_serie.huawei_equipment', function ($subQuery) use ($sap_codes){
                $subQuery->whereIn('claro_code', $sap_codes);
            });
        }

        if (count($request->selectedDUs) < $data['du_s']){
            $du_s = $request->selectedDUs;
            $query->whereIn('assigned_diu', $du_s);
        }

        // Obtener los datos finales con los filtros aplicados
        $equipments = $query->orderBy('created_at', 'desc')->get();
        foreach ($equipments as $equipment) {
            if ($equipment->huawei_equipment_serie && $equipment->huawei_equipment_serie->huawei_equipment) {
                $equipment->huawei_equipment_serie->huawei_equipment->name = $this->sanitizeText2($equipment->huawei_equipment_serie->huawei_equipment->name);
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
        foreach ($entries as $entry){
            if ($entry->state == 'Devuelto'){
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


    public function detailsWithoutDiu ($id)
    {
        $entries = HuaweiEntryDetail::whereNull('assigned_diu')
            ->whereHas('huawei_equipment_serie', function ($query) use ($id) {
            $query->whereHas('huawei_equipment', function ($query) use ($id) {
                $query->where('id', $id);
            });
        })
        ->with('huawei_entry', 'huawei_equipment_serie.huawei_equipment', 'latest_huawei_project_resource.huawei_project')
        ->get();

        foreach ($entries as $entry){
            $entry->huawei_equipment_serie->huawei_equipment->name = $this->sanitizeText2($entry->huawei_equipment_serie->huawei_equipment->name);
        }

        return Inertia::render('Huawei/Details', [
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
            // Filtrar por equipo
            $query->whereHas('huawei_equipment_serie', function ($subQuery) use ($id) {
                $subQuery->where('huawei_equipment_id', $id);
            })->whereNull('huawei_material_id');

            // Filtros específicos aplicados a la consulta base
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
                                $projectQuery->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"])
                                             ->orWhereRaw('LOWER(ot) LIKE ?', ["%{$searchTerm}%"]);
                            });
            });

            // Consulta separada para `queryByCode`
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

            // Combinar ambos conjuntos de resultados
            $mergedResults = $query->with('huawei_entry', 'huawei_pending_order', 'huawei_equipment_serie.huawei_equipment', 'latest_huawei_project_resource.huawei_project')
                                    ->orderBy('created_at', 'desc')
                                    ->get()
                                   ->merge($queryByCode)
                                   ->unique('id');
        } else {
            // Filtrar por material
            $query->whereHas('huawei_material', function ($materialQuery) use ($id) {
                $materialQuery->where('id', $id);
            })
            ->whereRaw('LOWER(order_number) LIKE ?', ["%{$searchTerm}%"])
            ->orWhereHas('huawei_entry', function ($entryQuery) use ($searchTerm) {
                $entryQuery->whereRaw('LOWER(guide_number) LIKE ?', ["%{$searchTerm}%"]);
            });

            $mergedResults = $query->with('huawei_entry', 'huawei_pending_order', 'huawei_material')            ->orderBy('created_at', 'desc')
            ->get();
        }

        return Inertia::render('Huawei/Details', [
            'entries' => $mergedResults,
            'equipment' => $equipment,
            'id' => $id,
            'search' => $request
        ]);
    }


    public function assignDIU (Request $request)
    {
        $request->validate([
            'huawei_entry_detail_id' => 'required',
            'assigned_diu' => 'required'
        ]);

        $entry_detail = HuaweiEntryDetail::find($request->huawei_entry_detail_id);
        if ($entry_detail->state !== 'Disponible'){
            abort(403, 'Acción no permitida.');
        }

        $entry_detail->update([
            'assigned_diu' => $request->assigned_diu
        ]);

        return redirect()->back();
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

    public function getRefunds ($warehouse, $equipment = null)
    {
        if ($equipment){
            $refunds = HuaweiRefund::with('huawei_entry_detail.huawei_equipment_serie.huawei_equipment', 'huawei_entry_detail.huawei_entry')
                ->whereHas('huawei_entry_detail', function ($query) {
                    $query->whereNull('huawei_material_id');
                })
                ->whereHas('huawei_entry_detail.huawei_equipment_serie.huawei_equipment', function ($query) use ($warehouse){
                    $query->where('prefix', $warehouse);
                })
                ->paginate(10);
        }else{
            $refunds = HuaweiRefund::with('huawei_entry_detail.huawei_material', 'huawei_entry_detail.huawei_entry')
                ->whereHas('huawei_entry_detail', function ($query) {
                    $query->whereNull('huawei_equipment_serie_id');
                })
                ->whereHas('huawei_entry_detail.huawei_material', function ($query) use ($warehouse){
                    $query->where('prefix', $warehouse);
                })
                ->paginate(10);
        }

        foreach ($refunds as $refund) {
            if ($equipment) {
                $refund->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name = $this->sanitizeText2($refund->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name);
            } else {
                $refund->huawei_entry_detail->huawei_material->name = $this->sanitizeText2($refund->huawei_entry_detail->huawei_material->name);
            }
        }

        return Inertia::render('Huawei/Refunds', [
            'refunds' => $refunds,
            'equipment' => $equipment,
            'warehouse' => $warehouse
        ]);
    }

    public function searchRefunds ($warehouse, $request, $equipment = null)
    {
        $searchTerm = strtolower($request);

        $query = HuaweiRefund::query();

        if ($equipment){
            $query->whereHas('huawei_entry_detail.huawei_equipment_serie.huawei_equipment', function ($query) use ($warehouse){
                $query->where('prefix', $warehouse);
            })
            ->whereHas('huawei_entry_detail', function ($query){
                $query->whereNull('huawei_material_id');
            });
            $query->where(function ($query) use ($searchTerm) {
                $query->whereHas('huawei_entry_detail.huawei_equipment_serie.huawei_equipment', function ($query) use ($searchTerm) {
                    $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"]);
                })
                ->orWhereHas('huawei_entry_detail.huawei_equipment_serie', function ($query) use ($searchTerm) {
                    $query->whereRaw('LOWER(serie_number) LIKE ?', ["%{$searchTerm}%"]);
                })
                ->orWhereHas('huawei_entry_detail.huawei_entry', function ($query) use ($searchTerm) {
                    $query->whereRaw('LOWER(guide_number) LIKE ?', ["%{$searchTerm}%"]);
                })
                ->orWhereHas('huawei_entry_detail', function ($query) use ($searchTerm){
                    $query->whereRaw('LOWER(order_number) LIKE ?', ["%{$searchTerm}%"]);
                });
            });
        }else{
            $query->whereHas('huawei_entry_detail.huawei_material', function ($query) use ($warehouse){
                $query->where('prefix', $warehouse);
            })
            ->whereHas('huawei_entry_detail', function ($query) {
                $query->whereNull('huawei_equipment_serie_id');
            });
            $query->where(function ($query) use ($searchTerm){
                $query->whereHas('huawei_entry_detail.huawei_material', function ($query) use ($searchTerm) {
                    $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"]);
                })
                ->orWhereHas('huawei_entry_detail.huawei_entry', function ($query) use ($searchTerm){
                    $query->whereRaw('LOWER(guide_number) LIKE ?', ["%{$searchTerm}%"]);
                })
                ->orWhereHas('huawei_entry_detail', function ($query) use ($searchTerm){
                    $query->whereRaw('LOWER(order_number) LIKE ?', ["%{$searchTerm}%"]);
                });
            });
        }

        $refunds = $query->with('huawei_entry_detail.huawei_equipment_serie.huawei_equipment', 'huawei_entry_detail.huawei_material', 'huawei_entry_detail.huawei_entry')->get();
        foreach ($refunds as $refund) {
            if ($equipment) {
                $refund->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name = $this->sanitizeText2($refund->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name);
            } else {
                $refund->huawei_entry_detail->huawei_material->name = $this->sanitizeText2($refund->huawei_entry_detail->huawei_material->name);
            }
        }

        return Inertia::render('Huawei/Refunds', [
            'refunds' => $refunds,
            'equipment' => $equipment,
            'search' => $request,
            'warehouse' => $warehouse
        ]);
    }

    public function exportInventory ()
    {
        return Excel::download(new HuaweiInventoryExport(), 'Inventario Huawei.xlsx');
    }

    public function getSpecialRefunds ()
    {
        return Inertia::render('Huawei/SpecialRefunds', [
            'refunds' => HuaweiSpecialRefund::orderBy('created_at', 'desc')->paginate(15)
        ]);
    }

    public function searchSpecialRefunds($request)
    {
        $searchTerm = strtolower($request);

        $refunds = HuaweiSpecialRefund::query()
            ->whereRaw('LOWER(description) LIKE ?', ["%{$searchTerm}%"])
            ->orWhereRaw('LOWER(diu) LIKE ?', ["%{$searchTerm}%"])
            ->orWhereRaw('LOWER(observation) LIKE ?', ["%{$searchTerm}%"])
            ->orWhereRaw('CAST(quantity AS CHAR) LIKE ?', ["%{$searchTerm}%"])
            ->get();

        return Inertia::render('Huawei/SpecialRefunds', [
            'refunds' => $refunds,
            'search' => $request
        ]);
    }

    public function storeSpecialRefund (Request $request)
    {
        $data = $request->validate([
            'description' => 'required',
            'diu' => 'required',
            'quantity' => 'required',
            'observation' => 'nullable'
        ]);

        HuaweiSpecialRefund::create($data);

        return redirect()->back();
    }

    public function updateSpecialRefund (HuaweiSpecialRefund $id, Request $request)
    {
        $data = $request->validate([
            'description' => 'required',
            'diu' => 'required',
            'quantity' => 'required',
            'observation' => 'required'
        ]);

        $id->update($data);
        return redirect()->back();
    }

    public function deleteSpecialRefund (HuaweiSpecialRefund $id)
    {
        $id->delete();

        return redirect()->back();
    }

    public function updateEntryDate (HuaweiEntryDetail $huawei_entry_detail, Request $request)
    {
        $data = $request->validate([
            'entry_date' => 'required'
        ]);

        $huawei_entry_detail->update($data);

        return redirect()->back();
    }

    public function updateSite (HuaweiEntryDetail $huawei_entry_detail, Request $request)
    {
        $data = $request->validate([
            'new_site' => 'required'
        ]);

        $huawei_entry_detail->update($data);

        return redirect()->back();
    }

    public function getPendingOrders ()
    {
        $pending_orders = HuaweiPendingOrder::where('status', 0)->with([
            'huawei_entry_details' => function ($query) {
                $query->select('id', 'quantity', 'unit_price', 'huawei_order_id', 'huawei_material_id', 'huawei_equipment_serie_id', 'observation') // Ajusta los campos que necesitas
                      ->with([
                          'huawei_material' => function ($subQuery) {
                              $subQuery->select('id', 'name', 'claro_code', 'unit');
                          },
                          'huawei_equipment_serie.huawei_equipment' => function ($subQuery) {
                              $subQuery->select('id', 'name', 'claro_code');
                          }
                      ]);
            }
        ])->paginate(20);

        $pending_orders->getCollection()->transform(function ($pendingOrder) {
            $pendingOrder->huawei_entry_details->each(function ($entryDetail) {
                $entryDetail->makeHidden(['state', 'refund_quantity', 'project_quantity', 'available_quantity', 'assigned_site', 'instalation_state', 'antiquation_state', 'huawei_project_resources', 'huawei_pending_order']); // Oculta los campos deseados
            });

            return $pendingOrder;
        });

        $order_numbers = $pending_orders->pluck('order_number')->toArray();

        return Inertia::render('Huawei/PendingOrders', [
            'pending_orders' => $pending_orders,
            'order_numbers' => $order_numbers
        ]);
    }

    public function ordersSearchAdvance (Request $request)
    {
        $pending_orders = HuaweiPendingOrder::where('status', 0)->with([
            'huawei_entry_details' => function ($query) {
                $query->select('id', 'quantity', 'unit_price', 'huawei_order_id', 'huawei_material_id', 'huawei_equipment_serie_id', 'observation') // Ajusta los campos que necesitas
                      ->with([
                          'huawei_material' => function ($subQuery) {
                              $subQuery->select('id', 'name', 'claro_code', 'unit');
                          },
                          'huawei_equipment_serie.huawei_equipment' => function ($subQuery) {
                              $subQuery->select('id', 'name', 'claro_code');
                          }
                      ]);
            }
        ]);

        $order_numbers = $pending_orders->get()->pluck('order_number')->filter()->unique()->count();
        $requestOrders = count($request->selectedOrderNumbers);

        if ($requestOrders < $order_numbers){
            $pending_orders->whereIn('order_number', $request->selectedOrderNumbers);
        }

        $finalOrders = $pending_orders->get()->each(function ($pendingOrder) {
            $pendingOrder->huawei_entry_details->each(function ($entryDetail) {
                $entryDetail->makeHidden([
                    'state',
                    'refund_quantity',
                    'project_quantity',
                    'available_quantity',
                    'assigned_site',
                    'instalation_state',
                    'antiquation_state',
                    'huawei_project_resources',
                    'huawei_pending_order'
                ]);
            });
        });

        return response()->json(['orders' => $finalOrders], 200);
    }

    public function orderAssignGuide (HuaweiPendingOrder $order, Request $request)
    {
        $data = $request->validate([
            'guide_number' => 'required',
            'entry_date' => 'required',
            'observation' => 'required'
        ]);

        $entry = HuaweiEntry::create($data);

        foreach ($order->huawei_entry_details as $entryDetail) {
            $entryDetail->update([
                'huawei_entry_id' => $entry->id
            ]);
        }

        $order->update([
            'status' => true
        ]);

        $pending_orders = HuaweiPendingOrder::where('status', 0)->with([
            'huawei_entry_details' => function ($query) {
                $query->select('id', 'quantity', 'unit_price', 'huawei_order_id', 'huawei_material_id', 'huawei_equipment_serie_id', 'observation') // Ajusta los campos que necesitas
                      ->with([
                          'huawei_material' => function ($subQuery) {
                              $subQuery->select('id', 'name', 'claro_code', 'unit');
                          },
                          'huawei_equipment_serie.huawei_equipment' => function ($subQuery) {
                              $subQuery->select('id', 'name', 'claro_code');
                          }
                      ]);
            }
        ]);

        $finalOrders = $pending_orders->get()->each(function ($pendingOrder) {
            $pendingOrder->huawei_entry_details->each(function ($entryDetail) {
                $entryDetail->makeHidden([
                    'state',
                    'refund_quantity',
                    'project_quantity',
                    'available_quantity',
                    'assigned_site',
                    'instalation_state',
                    'antiquation_state',
                    'huawei_project_resources',
                    'huawei_pending_order'
                ]);
            });
        });

        return response()->json(['orders' => $finalOrders], 200);
    }

    public function fetchPendingOrders ()
    {
        $pending_orders = HuaweiPendingOrder::where('status', 0)->get();
        return response()->json(['orders' => $pending_orders]);
    }
}
