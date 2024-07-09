<?php

namespace App\Http\Controllers\Huawei;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\HuaweiProject;
use App\Models\HuaweiProjectEmployee;
use App\Models\HuaweiSite;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\HuaweiAdditionalCost;
use App\Models\HuaweiEntryDetail;
use App\Models\HuaweiEquipment;
use App\Models\HuaweiMaterial;
use App\Models\HuaweiProjectResource;
use Illuminate\Validation\Rule;

class HuaweiProjectController extends Controller
{
    public function show ()
    {
        return Inertia::render('Huawei/Projects', [
            'projects' => HuaweiProject::paginate(10),
            'huawei_sites' => HuaweiSite::all()
        ]);
    }

    public function create ()
    {
        return Inertia::render('Huawei/ProjectForm', [
            'huawei_sites' => HuaweiSite::all(),
            'employees' => Employee::all()
        ]);
    }

    public function toUpdate (HuaweiProject $huawei_project)
    {
        return Inertia::render('Huawei/ProjectForm', [
            'huawei_project' => $huawei_project->load('huawei_site', 'huawei_project_employees.employee'),
            'huawei_sites' => HuaweiSite::all(),
            'employees' => Employee::all()
        ]);
    }

    public function store (Request $request)
    {
        $request->validate([
            'name' => 'required',
            'huawei_site_id' => 'required',
            'description' => 'nullable',
            'employees' => 'nullable'
        ]);

        $project = HuaweiProject::create([
            'name' => $request->name,
            'huawei_site_id' => $request->huawei_site_id,
            'description' => $request->description
        ]);

        if (!empty($request->employees)) {
            foreach ($request->employees as $employeeData) {
                HuaweiProjectEmployee::create([
                    'employee_id' => $employeeData['employee']['id'],
                    'huawei_project_id' => $project->id,
                    'role' => $employeeData['charge'],
                ]);
            }
        }

        return redirect()->back();
    }

    public function update (HuaweiProject $huawei_project, Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'huawei_site_id' => 'required',
            'description' => 'nullable'
        ]);

        $huawei_project->update($data);

        return redirect()->back();
    }

    public function deleteEmployee (HuaweiProjectEmployee $id)
    {
        $id->delete();

        return redirect()->back();
    }

    public function add_employee ($huawei_project, Request $request)
    {
        HuaweiProjectEmployee::create([
            'huawei_project_id' => $huawei_project,
            'employee_id' => $request->employee['id'],
            'role' => $request->charge

        ]);
        return redirect()->back();
    }

    //Sites

    public function getSites ()
    {
        return Inertia::render('Huawei/Sites', [
            'sites' => HuaweiSite::paginate(10)
        ]);
    }

    public function searchSites ($request)
    {
        $searchTerm = strtolower($request);

        $query = HuaweiSite::query();

        $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"]);

        return Inertia::render('Huawei/Sites', [
            'sites' => $query->get(),
            'search' => $request
        ]);
    }

    public function storeSite (Request $request)
    {
        $data = $request->validate([
            'name' => 'required'
        ]);

        HuaweiSite::create($data);

        return redirect()->back();
    }

    public function updateSite (HuaweiSite $site, Request $request)
    {
        $data = $request->validate([
            'name' => 'required'
        ]);

        $site->update($data);

        return redirect()->back();
    }

    public function verifySiteName(Request $request, $update = null)
    {
        $term = strtolower($request->input('name'));

        // Recuperar todos los nombres de HuaweiSite
        $sites = HuaweiSite::all()->pluck('name')->toArray();

        // Variable para almacenar el nombre de la coincidencia cercana
        $closeMatchName = null;

        // Comparar el término con los nombres existentes
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
        if ($update && $closeMatchName && $closeMatchName === HuaweiSite::find($update)->name) {
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


    //additional cost

    public function getAdditionalCosts (HuaweiProject $huawei_project)
    {
        $additional_costs = HuaweiAdditionalCost::where('huawei_project_id', $huawei_project->id)->paginate(10);
        return Inertia::render('Huawei/AdditionalCosts', [
            'additional_costs' => $additional_costs,
            'huawei_project' => $huawei_project
        ]);
    }

    public function storeAdditionalCosts ($huawei_project, Request $request)
    {
        $request->validate([
            'expense_type' => 'required',
            'ruc' => [
                'required',
                'digits:11',
                Rule::unique('huawei_additional_costs')->where(function ($query) use ($huawei_project) {
                    return $query->where('huawei_project_id', $huawei_project);
                })
            ],
            'zone' => 'required',
            'type_doc' => 'required',
            'doc_number' => [
                'required',
                Rule::unique('huawei_additional_costs')->where(function ($query) use ($huawei_project) {
                    return $query->where('huawei_project_id', $huawei_project);
                })
            ],
            'doc_date' => 'required',
            'amount' => 'required',
            'description' => 'nullable',
        ]);

        HuaweiAdditionalCost::create([
            'expense_type' => $request->expense_type,
            'ruc' => $request->ruc,
            'zone' => $request->zone,
            'type_doc' => $request->type_doc,
            'doc_number' => $request->doc_number,
            'doc_date' => $request->doc_date,
            'amount' => $request->amount,
            'description' => $request->description,
            'huawei_project_id' => $huawei_project
        ]);

        return redirect()->back();
    }

    public function updateAdditionalCosts ($huawei_project, HuaweiAdditionalCost $huawei_additional_cost, Request $request)
    {
        $data = $request->validate([
            'expense_type' => 'required',
            'ruc' => [
                'required',
                'digits:11',
                Rule::unique('huawei_additional_costs')->ignore($huawei_additional_cost->id)->where(function ($query) use ($huawei_project) {
                    return $query->where('huawei_project_id', $huawei_project);
                })
            ],
            'zone' => 'required',
            'type_doc' => 'required',
            'ruc' => [
                'required',
                Rule::unique('huawei_additional_costs')->ignore($huawei_additional_cost->id)->where(function ($query) use ($huawei_project) {
                    return $query->where('huawei_project_id', $huawei_project);
                })
            ],
            'doc_date' => 'required',
            'amount' => 'required',
            'description' => 'nullable',
        ]);

        $huawei_additional_cost->update($data);

        return redirect()->back();
    }

    public function deleteAdditionalCost ($huawei_project, HuaweiAdditionalCost $huawei_additional_cost)
    {
        $huawei_additional_cost->delete();
        return redirect()->back();
    }

    public function searchAdditionalCosts (HuaweiProject $huawei_project, $request)
    {
        $searchTerm = strtolower($request);

        $query = HuaweiAdditionalCost::query();
        $query->where(function ($query) use ($searchTerm) {
            $query->where('expense_type', 'like', '%' . $searchTerm . '%')
                  ->orWhere('ruc', 'like', '%' . $searchTerm . '%')
                  ->orWhere('zone', 'like', '%' . $searchTerm . '%')
                  ->orWhere('type_doc', 'like', '%' . $searchTerm . '%')
                  ->orWhere('doc_number', 'like', '%' . $searchTerm . '%');
        });
        $filteredAdditionalCosts = $query->get();
        return Inertia::render('Huawei/AdditionalCosts', [
            'additional_costs' => $filteredAdditionalCosts,
            'huawei_project' => $huawei_project,
            'search' => $request
        ]);
    }

    public function getResources($huawei_project, $equipment = null)
    {
        // Construir la consulta inicial
        $query = HuaweiProjectResource::where('huawei_project_id', $huawei_project);

        if ($equipment) {
            // Agregar relaciones específicas para equipos
            $query->with(['huawei_entry_detail.huawei_equipment_serie.huawei_equipment'])
                  ->whereHas('huawei_entry_detail', function ($query) {
                      $query->whereNotNull('huawei_equipment_serie_id')
                            ->whereNull('huawei_material_id');
                  });
            $resources = $query->paginate(10);
            $entryDetails = HuaweiEntryDetail::whereNull('huawei_material_id')
                ->with('huawei_equipment_serie.huawei_equipment')
                ->get()
                ->filter(function ($detail) {
                    return $detail->state === 'Disponible';
                });
            return Inertia::render('Huawei/Resources', [
                'resources' => $resources,
                'equipment' => $equipment,
                'equipments' => HuaweiEquipment::all(),
                'entry_details' => $entryDetails,
                'huawei_project' => $huawei_project
            ]);
        } else {
            // Agregar relaciones específicas para materiales
            $query->with(['huawei_entry_detail.huawei_material'])
                  ->whereHas('huawei_entry_detail', function ($query) {
                      $query->whereNotNull('huawei_material_id')
                            ->whereNull('huawei_equipment_serie_id');
                  });
            $resources = $query->paginate(10);
            $entryDetails = HuaweiEntryDetail::whereNull('huawei_equipment_serie_id')
                ->with('huawei_material')
                ->get()
                ->filter(function ($detail) {
                    return $detail->state === 'Disponible';
                });
            return Inertia::render('Huawei/Resources', [
                'resources' => $resources,
                'equipment' => $equipment,
                'materials' => HuaweiMaterial::all(),
                'entry_details' => $entryDetails,
                'huawei_project' => $huawei_project
            ]);
        }
    }

    public function storeProjectResource ($huawei_project, Request $request, $equipment = null)
    {
        if ($equipment){
            $request->validate([
                'huawei_entry_detail_id' => 'required',
            ]);

            HuaweiProjectResource::create([
                'huawei_project_id' => $huawei_project,
                'huawei_entry_detail_id' => $request->huawei_entry_detail_id,
                'quantity' => 1
            ]);

        } else{
            $request->validate([
                'huawei_entry_detail_id' => 'required',
                'quantity' => 'required'
            ]);

            HuaweiProjectResource::create([
                'huawei_project_id' => $huawei_project,
                'huawei_entry_detail_id' => $request->huawei_entry_detail_id,
                'quantity' => $request->quantity
            ]);
        }

        return redirect()->back();
    }
}
