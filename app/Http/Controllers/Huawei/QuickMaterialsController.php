<?php

namespace App\Http\Controllers\Huawei;

use App\Http\Controllers\Controller;
use App\Http\Requests\Huawei\QuickMaterialsOutputRequest;
use App\Http\Requests\Huawei\QuickMaterialsRequest;
use App\Models\HuaweiProject;
use App\Models\HuaweiSite;
use App\Models\QuickMaterial;
use App\Models\QuickMaterialsEntry;
use App\Models\QuickMaterialsOutput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

use function Pest\Laravel\json;

class QuickMaterialsController extends Controller
{
    //quick_materials

    public function getMaterials ()
    {
        return Inertia::render('Huawei/QuickMaterials', [
            'quick_materials' => QuickMaterial::orderBy('created_at', 'desc')->paginate(15)
        ]);
    }

    public function searchMaterial ($request)
    {
        $searchTerm = strtolower($request);

        $query = QuickMaterial::query();

        $query->whereRaw('LOWER (description) LIKE ? ', ["%{$searchTerm}%"])
               ->orWhereRaw('LOWER (unit) LIKE ? ', ["%{$searchTerm}%"]);

        return Inertia::render('Huawei/QuickMaterials', [
            'quick_materials' => $query->orderBy('created_at', 'desc')->get(),
            'search' => $request
        ]);
    }

    public function storeMaterial (Request $request)
    {
        $data = $request->validate([
            'description' => 'required',
            'unit' => 'required'
        ]);

        QuickMaterial::create($data);

        return redirect()->back();
    }

    public function updateMaterial (QuickMaterial $material, Request $request)
    {
        $data = $request->validate([
            'description' => 'required',
            'unit' => 'required'
        ]);

        $material->update($data);

        return redirect()->back();
    }

    public function deleteMaterial (QuickMaterial $material)
    {
        $material->delete();

        return redirect()->back();
    }

    public function verifyName(Request $request, $update = null)
    {
        $request->validate([
            'description' => 'required',
            'unit' => 'required'
        ]);

        $term = strtolower($request->input('description'));

        $sites = QuickMaterial::all()->pluck('description')->toArray();

        $closeMatchName = null;

        foreach ($sites as $site) {
            $similarity = 0;
            similar_text($term, strtolower($site), $similarity);

            // Considerar un nombre como "cercano" si la similitud es mayor al 80%
            if ($similarity > 80) {
                $closeMatchName = $site;
                break;
            }
        }

        // Verificar si estamos en modo de actualización y el nombre encontrado es el mismo que el nombre actual
        if ($update && $closeMatchName && $closeMatchName === QuickMaterial::find($update)->description) {
            return response()->json([
                'message' => 'notfound',
                'status' => 'none'
            ]);
        }

        // Construir la respuesta
        if ($closeMatchName) {
            return response()->json([
                'message' => 'found',
                'status' => 'close',
                'name' => $closeMatchName
            ]);
        } else {
            return response()->json([
                'message' => 'notfound',
                'status' => 'none'
            ]);
        }
    }

    //details
    public function index($material_id)
    {
        $material = QuickMaterial::find($material_id);

        // Obtiene los datos con la relación quick_materials_outputs y las subrelaciones
        $quickMaterials = QuickMaterialsEntry::where('quick_material_id', $material_id)
            ->with(['quick_materials_outputs' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }, 'quick_materials_outputs.huawei_project.huawei_site'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Iteramos sobre los resultados para añadir los campos 'project' y 'site'
        $quickMaterials->getCollection()->transform(function ($entry) {
            $entry->quick_materials_outputs->transform(function ($output) {
                $output->project = optional($output->huawei_project)->assigned_diu;
                $output->site = optional($output->huawei_project?->huawei_site)->name;
                return $output;
            });
            return $entry;
        });

        // Retornamos la vista con los datos transformados
        return Inertia::render('Huawei/QuickMaterialsDetails', [
            'quickMaterials' => $quickMaterials,
            'material' => $material,
            'sites' => HuaweiSite::select('id', 'name')->get()
        ]);
    }


    public function store($material_id, QuickMaterialsRequest $request) {
        $originalData = $request->all();
        if (empty($originalData)) {
            return response()->json(['message' => 'No data provided'], 400);
        }
        $data = $request->validated();
        $quick_res = QuickMaterialsEntry::updateOrCreate(['id' => $request->id, 'quick_material_id' => $material_id], $data);
        $quick_res->load('quick_materials_outputs');
        return response()->json(['quick_res'=> $quick_res], 200);
    }


    public function destroy (QuickMaterialsEntry $quick_material) {
        $quick_material->delete();
        return response()->json(['message'=>'success'], 200);
    }

    //outputs
    public function storeOutput ($entry_id, QuickMaterialsOutputRequest $request)
    {
        $originalData = $request->all();

        if (empty($originalData)){
            return response()->json(['message' => $originalData]);
        }

        $data = $request->validated();

        $quick_res_out = QuickMaterialsOutput::updateOrCreate(['id' => $request->id, 'quick_material_entry_id' => $entry_id], $data);
        return response()->json(['quick_res_out' => $quick_res_out], 200);
    }

    public function destroyOutput (QuickMaterialsOutput $output) {
        $output->delete();
        return response()->json(['message'=>'success', $output], 200);
    }

    public function fetchProjects ($site_id)
    {
        $projects = HuaweiProject::select('id', 'name', 'assigned_diu')->where('huawei_site_id', $site_id)->get();
        return response()->json(['projects' => $projects]);
    }

    public function selectProject($entry_id, $project_id, $output_id = null)
    {
        // Obtiene el proyecto con su relación huawei_site
        $project = HuaweiProject::with('huawei_site')->find($project_id)->setAppends([]);

        if ($output_id) {
            // Si se proporciona un output_id, actualiza la salida existente
            $quick_res_project = QuickMaterialsOutput::find($output_id);
            $quick_res_project->update([
                'quick_material_entry_id' => $entry_id,
                'huawei_project_id' => $project_id
            ]);
        } else {
            // Si no se proporciona output_id, crea una nueva entrada
            $quick_res_project = QuickMaterialsOutput::create([
                'quick_material_entry_id' => $entry_id,
                'huawei_project_id' => $project_id
            ]);
        }

        // Convertimos el objeto en JSON para poder agregarle los campos adicionales
        $quick_res_project = $quick_res_project->toArray();

        // Añadimos los campos adicionales que quieres devolver en la respuesta
        $quick_res_project['project'] = $project->assigned_diu;
        $quick_res_project['site'] = $project->huawei_site->name;

        // Devolvemos la respuesta JSON con los campos adicionales
        return response()->json(['quick_res_project' => $quick_res_project], 200);
    }

}

