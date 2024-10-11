<?php

namespace App\Http\Controllers\Huawei;

use App\Http\Controllers\Controller;
use App\Http\Requests\Huawei\QuickMaterialsOutputRequest;
use App\Http\Requests\Huawei\QuickMaterialsRequest;
use App\Models\QuickMaterial;
use App\Models\QuickMaterialsEntry;
use App\Models\QuickMaterialsOutput;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
    public function index($material_id) {
        $material = QuickMaterial::find($material_id);
        return Inertia::render('Huawei/QuickMaterialsDetails', [
                'quickMaterials' => QuickMaterialsEntry::where('quick_material_id', $material_id)->with('quick_materials_outputs')->orderBy('created_at', 'desc')->paginate(20),
                'material' => $material
            ]
        );
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
    public function storeOutput ($entry_id, Request $request)
    {
        $originalData = $request->all();
        $object = [
            'output_date' => $request->output_date,
            'quantity' => $request->quantity2,
            'employee' => $request->employee2,
            'observation' => $request->observation2
        ];

        if (empty($originalData)){
            return response()->json(['message' => $originalData]);
        }

        $quick_res_out = QuickMaterialsOutput::updateOrCreate(['id' => $request->id, 'quick_material_entry_id' => $entry_id], $object);
        return response()->json(['quick_res_out' => $quick_res_out], 200);
    }
}
