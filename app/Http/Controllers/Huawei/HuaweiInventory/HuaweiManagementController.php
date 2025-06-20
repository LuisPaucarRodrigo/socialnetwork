<?php

namespace App\Http\Controllers\Huawei\HuaweiInventory;

use App\Constants\HuaweiConstants;
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
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Throwable;

class HuaweiManagementController extends Controller
{

    private static array $data;

    public function __construct()
    {
        self::$data = [
            'operators' => HuaweiConstants::getOperators(),
        ];
    }

    public function show($warehouse, $equipment = null)
    {
        if ($equipment) {
            $equipments = HuaweiEquipment::select('id', 'name', 'claro_code', 'model_id')->with('brand_model.brand')->where('prefix', $warehouse)->paginate(10);
            foreach ($equipments as $item) {
                $item->name = $this->sanitizeText2($item->name);
            }

            return Inertia::render('Huawei/HuaweiInventory/Inventory', [
                'equipments' => $equipments,
                'brand_models' => BrandModel::all(),
                'brands' => Brand::all(),
                'equipment' => $equipment,
                'warehouse' => $warehouse,
                'data' => self::$data
            ]);
        } else {
            $materials = HuaweiMaterial::select('id', 'name', 'claro_code', 'model_id')->with('brand_model.brand')->where('prefix', $warehouse)->paginate(10);
            $materials->getCollection()->makeHidden(['huawei_entry_details']);
            foreach ($materials as $material) {
                $material->name = $this->sanitizeText2($material->name);
            }
            return Inertia::render('Huawei/HuaweiInventory/Inventory', [
                'materials' => $materials,
                'brand_models' => BrandModel::all(),
                'brands' => Brand::all(),
                'equipment' => $equipment,
                'warehouse' => $warehouse,
                'data' => self::$data
            ]);
        }
    }

    public function searchIndex($warehouse, $request, $equipment = null)
    {
        $searchTerm = strtolower($request);

        if ($equipment) {
            // Consulta para equipos
            $equipmentsQuery = HuaweiEquipment::query();

            // Aplicar filtros de búsqueda para equipos
            $equipmentsQuery->select('id', 'name', 'claro_code', 'model_id')->where('prefix', $warehouse)->where(function ($query) use ($searchTerm) {
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
            $equipments = $equipmentsQuery->with('brand_model.brand')->get()
                ->map(function ($equipment) {
                    $equipment->name = $this->sanitizeText2($equipment->name);
                    return $equipment;
                });

            // Retornar la vista para equipos
            return Inertia::render('Huawei/HuaweiInventory/Inventory', [
                'equipments' => $equipments,
                'brand_models' => BrandModel::all(),
                'brands' => Brand::all(),
                'equipment' => $equipment,
                'search' => $request,
                'warehouse' => $warehouse,
                'data' => self::$data
            ]);
        } else {
            $materialsQuery = HuaweiMaterial::query();

            // Aplicar filtros de búsqueda para materiales
            $materialsQuery->select('id', 'name', 'claro_code', 'model_id')->where('prefix', $warehouse)->where(function ($query) use ($searchTerm) {
                $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(claro_code) LIKE ?', ["%{$searchTerm}%"]);
            });

            // Obtener los resultados de materiales
            $materials = $materialsQuery->with('brand_model.brand')->get()
                ->map(function ($material) {
                    $material->name = $this->sanitizeText2($material->name);
                    return $material;
                });

            $materials->makeHidden(['huawei_entry_details']);

            // Retornar la vista para materiales
            return Inertia::render('Huawei/HuaweiInventory/Inventory', [
                'materials' => $materials,
                'brand_models' => BrandModel::all(),
                'brands' => Brand::all(),
                'equipment' => $equipment,
                'search' => $request,
                'warehouse' => $warehouse,
                'data' => self::$data
            ]);
        }
    }


    public function create()
    {
        return Inertia::render('Huawei/HuaweiInventory/InventoryStore', [
            'brand_models' => BrandModel::all(),
            'brands' => Brand::all(),
        ]);
    }

    public function getInventoryPerWarehouse($value)
    {
        $materials = null;
        $equipments = null;

        $materials = HuaweiMaterial::select('id', 'name', 'model_id', 'prefix', 'claro_code', 'unit')->where('prefix', $value)->with('brand_model:id,name,brand_id')->get();
        $equipments = HuaweiEquipment::select('id', 'name', 'model_id', 'prefix', 'claro_code', 'unit')->where('prefix', $value)->with('brand_model:id,name,brand_id')->get();

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

    private function sanitizeText2($text)
    {
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
            'archive' => 'required|mimes:pdf,jpg,jpeg,png|max:2048',
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

        $filename = '';
        if ($request->hasFile('archive')) {
            $file = $request->file('archive');
            $filename = time() . '_' . $request->guide_number . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('documents/huawei/guides/'), $filename);
        }
        // Crear entrada
        $huawei_entry = HuaweiEntry::create([
            'guide_number' => $request->guide_number,
            'entry_date' => $request->entry_date,
            'observation' => $request->observation,
            'archive' => $filename
        ]);

        $warehouse = $request->warehouse;

        try {
            // Manejar materiales
            if ($request->materials) {
                foreach ($request->materials as $material) {
                    if (!$material['order_number'] && !$request->order_number) {
                        DB::rollBack();
                        abort(403, 'Acción no permitida.');
                    }
                    if (!$material['order_date'] && !$request->order_date) {
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

                            if ($existing_serie) {
                                DB::rollBack();
                                return response()->json(['error' => 'Ocurrió un error durante la inserción de datos o se encontraron duplicados'], 500);
                            }

                            if (!$serie['order_number'] && !$request->order_number) {
                                DB::rollBack();
                                abort(403, 'Acción no permitida.');
                            }

                            if (!$serie['order_date'] && !$request->order_date) {
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
                            if (!$serie['order_number'] && !$request->order_number) {
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

    public function showGuide(HuaweiEntry $entry)
    {
        $filename = $entry->archive;
        $filePath = '/documents/huawei/guides/' . $filename;
        $path = public_path($filePath);
        if (file_exists($path)) {
            ob_end_clean();
            return response()->file($path);
        }
        abort(404, 'Archivo no encontrado');
    }

    public function verifySerie(Request $request, HuaweiEquipment $equipment)
    {
        $existingSeries = $equipment->huawei_equipment_series->pluck('serie_number')->toArray();

        if (in_array($request->input('serie_number'), $existingSeries)) {
            return response()->json(['message' => 'found'], 200);
        }

        return response()->json(['message' => 'notfound'], 200);
    }

    public function storeBrand(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', Rule::unique('brands')],
        ]);

        $new = Brand::create($data);

        return response()->json(['new' => $new], 200);
    }

    public function storeBrandModel(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'brand_id' => 'required'
        ]);

        $new = BrandModel::create($data);

        return response()->json(['new' => $new], 200);
    }
}
