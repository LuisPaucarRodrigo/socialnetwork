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
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;

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
            ->with('huawei_entry', 'huawei_equipment_serie.huawei_equipment')
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

        $query = HuaweiEntryDetail::query();

        if ($equipment) {
            // Búsqueda por número de serie en equipos
            $query->whereHas('huawei_equipment_serie', function ($query) use ($searchTerm, $id) {
                $query->where('huawei_equipment_id', $id)
                      ->whereRaw('LOWER(serie_number) LIKE ?', ["%{$searchTerm}%"]);
            });

            // Búsqueda por nombre del equipo
            $query->orWhereHas('huawei_equipment_serie.huawei_equipment', function ($query) use ($searchTerm, $id) {
                $query->where('id', $id)
                      ->whereHas('huawei_equipment_series', function ($query) use ($searchTerm) {
                          $query->whereHas('huawei_equipment', function ($query) use ($searchTerm) {
                              $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"]);
                          });
                      });
            });
        } else {
            $query->whereHas('huawei_material', function ($query) use ($searchTerm) {
                $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"]);
            });
       }

       $entries = $query->with('huawei_entry', 'huawei_equipment_serie.huawei_equipment', 'huawei_material')
       ->get();

        return Inertia::render('Huawei/Details', [
            'entries' => $entries,
            'equipment' => $equipment,
            'id' => $id,
            'search' => $request
        ]);
    }

}
