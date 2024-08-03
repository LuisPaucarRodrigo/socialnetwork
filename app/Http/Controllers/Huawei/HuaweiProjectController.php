<?php

namespace App\Http\Controllers\Huawei;

use App\Exports\HuaweiProjectEarningsExport;
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
use App\Models\HuaweiProjectEarning;
use App\Models\HuaweiProjectLiquidation;
use App\Models\HuaweiProjectResource;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\HuaweiPriceGuide;
use Maatwebsite\Excel\Facades\Excel;

class HuaweiProjectController extends Controller
{
    public function show ()
    {
        return Inertia::render('Huawei/Projects', [
            'projects' => HuaweiProject::where('status', 1)->with('huawei_site')->orderBy('updated_at', 'desc')->paginate(10),
        ]);
    }

    public function projectHistory ()
    {
        return Inertia::render('Huawei/ProjectsHistory', [
            'projects' => HuaweiProject::where('status', 0)->with('huawei_site')->orderBy('updated_at', 'desc')->paginate(10),
        ]);
    }

    public function searchProjectHistory ($request)
    {
        $searchTerm = strtolower($request);
        $projects = HuaweiProject::where('status', 0)->where(function ($query) use ($searchTerm) {
            $query->whereRaw('LOWER(name) like ?', ['%'.$searchTerm.'%'])
                  ->orWhereRaw('LOWER(description) like ?', ['%'.$searchTerm.'%'])
                  ->orWhereRaw('LOWER(ot) LIKE ?', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(assigned_diu) LIKE ?', ["%{$searchTerm}%"])
                  ->orWhereHas('huawei_site', function ($query) use ($searchTerm){
                    $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"]);
                });
        })->with('huawei_site')->orderBy('updated_at', 'desc')->get();

        return Inertia::render('Huawei/ProjectsHistory', [
            'projects' => $projects,
            'search' => $request,
        ]);
    }

    public function searchProject ($request)
    {
        $searchTerm = strtolower($request);
        $projects = HuaweiProject::where('status', 1)->where(function ($query) use ($searchTerm) {
            $query->whereRaw('LOWER(name) like ?', ['%'.$searchTerm.'%'])
                  ->orWhereRaw('LOWER(description) like ?', ['%'.$searchTerm.'%'])
                  ->orWhereRaw('LOWER(ot) LIKE ?', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(assigned_diu) LIKE ?', ["%{$searchTerm}%"])
                  ->orWhereHas('huawei_site', function ($query) use ($searchTerm){
                    $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"]);
                  });
        })->with('huawei_site')->orderBy('updated_at', 'desc')->get();

        return Inertia::render('Huawei/Projects', [
            'projects' => $projects,
            'search' => $request,
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
        if (!$huawei_project->status){
            abort(403, 'Acción no permitida');
        }
        return Inertia::render('Huawei/ProjectForm', [
            'huawei_project' => $huawei_project->load('huawei_site', 'huawei_project_employees.employee'),
            'huawei_sites' => HuaweiSite::all(),
            'employees' => Employee::all()
        ]);
    }

    public function liquidateProject (HuaweiProject $huawei_project)
    {
        if (!$huawei_project->status){
            abort(403, 'Acción no permitida');
        }

        if (!$huawei_project->state){
            abort(403, 'Proyecto no apto para liquidación');
        }

        $huawei_project->update([
            'status' => false
        ]);

        return redirect()->back();
    }

    public function cancelProject (HuaweiProject $huawei_project)
    {
        if (!$huawei_project->status){
            abort(403, 'Acción no permitida');
        }

        $huawei_project->update([
            'status' => null
        ]);

        return redirect()->back();
    }

    public function showStoppedProjects()
    {
        $projects = HuaweiProject::whereNull('status')->with('huawei_site')->orderBy('updated_at', 'desc')->paginate(10);
        return Inertia::render('Huawei/StoppedProjects', [
            'projects' => $projects
        ]);
    }

    public function searchStoppedProjects ($request)
    {
        $searchTerm = strtolower($request);
        $projects = HuaweiProject::where('status', null)->where(function ($query) use ($searchTerm) {
            $query->whereRaw('LOWER(name) like ?', ['%'.$searchTerm.'%'])
                  ->orWhereRaw('LOWER(description) like ?', ['%'.$searchTerm.'%'])
                  ->orWhereRaw('LOWER(ot) LIKE ?', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(assigned_diu) LIKE ?', ["%{$searchTerm}%"])
                  ->orWhereHas('huawei_site', function ($query) use ($searchTerm){
                    $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"]);
                  });
        })->with('huawei_site')->orderBy('updated_at', 'desc')->get();

        return Inertia::render('Huawei/StoppedProjects', [
            'projects' => $projects,
            'search' => $request,
        ]);
    }

    public function resumeProject (HuaweiProject $huawei_project)
    {
        if ($huawei_project->status !== null){
            abort(403, 'Acción no permitida');
        }

        $huawei_project->update([
            'status' => 1
        ]);

        return redirect()->back();
    }

    public function projectBalance (HuaweiProject $huawei_project)
    {
        if (!$huawei_project->pre_report)
        {
            abort(403, 'Acción no permitida');
        }

        return Inertia::render('Huawei/ProjectBalance', [
            'huawei_project' => $huawei_project
        ]);
    }

    public function store (Request $request)
    {
        $request->validate([
            'name' => 'required',
            'huawei_site_id' => 'required',
            'description' => 'nullable',
            'ot' => 'nullable',
            'pre_report' => 'nullable',
            'employees' => 'nullable',
            'initial_amount' => 'required',
            'assigned_diu' => 'required'
        ]);

        if ($request->hasFile('pre_report')){
            $documentName = null;
            $document = $request->file('pre_report');
            $documentName = time() . '_' . $document->getClientOriginalName();
            $document->move(public_path('documents/huawei/'), $documentName);

            $project = HuaweiProject::create([
                'name' => $request->name,
                'huawei_site_id' => $request->huawei_site_id,
                'description' => $request->description,
                'ot' => $request->ot,
                'pre_report' => $documentName,
                'initial_amount' => $request->initial_amount,
                'assigned_diu' => $request->assigned_diu
            ]);
        }else{
            $project = HuaweiProject::create([
                'name' => $request->name,
                'huawei_site_id' => $request->huawei_site_id,
                'description' => $request->description,
                'ot' => $request->ot,
                'initial_amount' => $request->initial_amount,
                'assigned_diu' => $request->assigned_diu
            ]);
        }

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
        if (!$huawei_project->status){
            abort(403, 'Acción no permitida');
        }

        $data = $request->validate([
            'name' => 'required',
            'huawei_site_id' => 'required',
            'description' => 'nullable',
            'ot' => 'nullable',
            'pre_report' => 'nullable',
            'initial_amount' => 'required',
            'assigned_diu' => 'required'
        ]);

        if ($request->hasFile('pre_report')){
            $fileName = $huawei_project->pre_report;
            $filePath = "documents/huawei/$fileName";
            $path = public_path($filePath);
            if (file_exists($path) && $huawei_project->pre_report){
                unlink($path);
            }
            $documentName = null;
            $document = $request->file('pre_report');
            $documentName = time() . '-' . $document->getClientOriginalName();
            $document->move(public_path('documents/huawei/'), $documentName);
            $huawei_project->update([
                'name' => $request->name,
                'huawei_site_id' => $request->huawei_site_id,
                'description' => $request->description,
                'ot' => $request->ot,
                'pre_report' => $documentName,
                'initial_amount' => $request->initial_amount,
                'assigned_diu' => $request->assigned_diu
            ]);
        }else{
            $huawei_project->update([
                'name' => $request->name,
                'huawei_site_id' => $request->huawei_site_id,
                'description' => $request->description,
                'ot' => $request->ot,
                'initial_amount' => $request->initial_amount,
                'assigned_diu' => $request->assigned_diu
            ]);
        }


        return redirect()->back();
    }

    public function showPreReport (HuaweiProject $huawei_project)
    {
        if (!$huawei_project->pre_report){
            abort(403, 'Este proyecto no cuenta con un reporte');
        }

        $fileName = $huawei_project->pre_report;
        $filePath = '/documents/huawei/' . $fileName;
        $path = public_path($filePath);
        if (file_exists($path)) {
            ob_end_clean();
            return response()->file($path);
        }
        abort(404, 'Documento no encontrado');
    }

    public function deleteEmployee (HuaweiProjectEmployee $id)
    {
        $huawei_project = HuaweiProject::find($id->huawei_project->id);

        if (!$huawei_project->status){
            abort(403, 'Acción no permitida');
        }

        $id->delete();

        return redirect()->back();
    }

    public function add_employee (HuaweiProject $huawei_project, Request $request)
    {
        if (!$huawei_project->status){
            abort(403, 'Acción no permitida');
        }

        HuaweiProjectEmployee::create([
            'huawei_project_id' => $huawei_project->id,
            'employee_id' => $request->employee['id'],
            'role' => $request->charge

        ]);
        return redirect()->back();
    }

    //Sites

    public function getSites ()
    {
        return Inertia::render('Huawei/Sites', [
            'sites' => HuaweiSite::orderBy('updated_at', 'desc')->paginate(10)
        ]);
    }

    public function searchSites ($request)
    {
        $searchTerm = strtolower($request);

        $query = HuaweiSite::query();

        $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"]);

        return Inertia::render('Huawei/Sites', [
            'sites' => $query->orderBy('updated_at', 'desc')->get(),
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
        if (!$huawei_project->pre_report){
            abort(403, 'Acción no permitida');
        }
        $additional_costs = HuaweiAdditionalCost::where('huawei_project_id', $huawei_project->id)->paginate(10);
        return Inertia::render('Huawei/AdditionalCosts', [
            'additional_costs' => $additional_costs,
            'huawei_project' => $huawei_project
        ]);
    }

    public function storeAdditionalCosts ($huawei_project, Request $request)
    {
        $found_project = HuaweiProject::find($huawei_project);

        if (!$found_project->status || !$found_project->pre_report){
            abort(403, 'Acción no permitida');
        }

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
        $found_project = HuaweiProject::find($huawei_project);

        if (!$found_project->status || !$found_project->pre_report){
            abort(403, 'Acción no permitida');
        }

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
        $found_project = HuaweiProject::find($huawei_project);

        if (!$found_project->status || !$found_project->pre_report){
            abort(403, 'Acción no permitida');
        }

        $huawei_additional_cost->delete();
        return redirect()->back();
    }

    public function searchAdditionalCosts (HuaweiProject $huawei_project, $request)
    {
        if (!$huawei_project->pre_report){
            abort(403, 'Acción no permitida');
        }

        $searchTerm = strtolower($request);

        $query = HuaweiAdditionalCost::query();
        $query->where('huawei_project_id', $huawei_project->id)->where(function ($query) use ($searchTerm) {
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
        $found_project = HuaweiProject::find($huawei_project);

        if (!$found_project->pre_report){
            abort(403, 'Acción no permitida');
        }
        // Construir la consulta inicial
        $query = HuaweiProjectResource::where('huawei_project_id', $huawei_project)->with('huawei_project_liquidation');
        $project_state = HuaweiProject::find($huawei_project)->status;
        $huawei_project_name_code = HuaweiProject::find($huawei_project)->name . ' / ' . HuaweiProject::find($huawei_project)->code;
        if ($equipment) {
            // Agregar relaciones específicas para equipos
            $query->with(['huawei_entry_detail.huawei_equipment_serie.huawei_equipment'])
                  ->whereHas('huawei_entry_detail', function ($query) {
                      $query->whereNotNull('huawei_equipment_serie_id')
                            ->whereNull('huawei_material_id');
                  });
            $resources = $query->paginate(10);
            $entryDetails = HuaweiEntryDetail::whereNull('huawei_material_id')
                ->where('assigned_diu', $found_project->assigned_diu)
                ->with('huawei_equipment_serie.huawei_equipment')
                ->get()
                ->filter(function ($detail) {
                    return $detail->state === 'Disponible';
                });
                $equipments = HuaweiEquipment::whereHas('huawei_equipment_series.huawei_entry_detail', function ($query) use ($found_project) {
                    $query->where('assigned_diu', $found_project->assigned_diu);
                })->get();
            return Inertia::render('Huawei/Resources', [
                'resources' => $resources,
                'equipment' => $equipment,
                'equipments' => $equipments,
                'entry_details' => $entryDetails,
                'huawei_project' => $huawei_project,
                'huawei_project_name_code' => $huawei_project_name_code,
                'project_state' => $project_state
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
                'huawei_project' => $huawei_project,
                'huawei_project_name_code' => $huawei_project_name_code,
                'project_state' => $project_state
            ]);
        }
    }

    public function searchResources ($huawei_project, $request, $equipment = null)
    {
        $found_project = HuaweiProject::find($huawei_project);

        if (!$found_project->pre_report){
            abort(403, 'Acción no permitida');
        }

        $searchTerm = strtolower($request);
        $query = HuaweiProjectResource::where('huawei_project_id', $huawei_project);
        $project_state = HuaweiProject::find($huawei_project)->status;
        $huawei_project_name_code = HuaweiProject::find($huawei_project)->name . ' / ' . HuaweiProject::find($huawei_project)->code;

        if ($equipment) {
            // Agregar relaciones específicas para equipos
            $query->with(['huawei_entry_detail.huawei_equipment_serie.huawei_equipment'])
            ->whereHas('huawei_entry_detail', function ($query) use ($searchTerm) {
                $query->whereNotNull('huawei_equipment_serie_id')
                      ->whereNull('huawei_material_id')
                      ->whereHas('huawei_equipment_serie.huawei_equipment', function ($query) use ($searchTerm) {
                          $query->where('name', 'like', '%'.$searchTerm.'%');
                      })
                      ->orWhereHas('huawei_equipment_serie', function ($query) use ($searchTerm) {
                          $query->where('serie_number', 'like', '%'.$searchTerm.'%');
                      });
            });
            $resources = $query->get();
            $entryDetails = HuaweiEntryDetail::whereNull('huawei_material_id')
                ->where('assigned_diu', $found_project->assigned_diu)
                ->with('huawei_equipment_serie.huawei_equipment')
                ->get()
                ->filter(function ($detail) {
                    return $detail->state === 'Disponible';
                });
                $equipments = HuaweiEquipment::whereHas('huawei_equipment_series.huawei_entry_detail', function ($query) use ($found_project) {
                    $query->where('assigned_diu', $found_project->assigned_diu);
                })->get();
            return Inertia::render('Huawei/Resources', [
                'resources' => $resources,
                'equipment' => $equipment,
                'equipments' => $equipments,
                'entry_details' => $entryDetails,
                'huawei_project' => $huawei_project,
                'huawei_project_name_code' => $huawei_project_name_code,
                'project_state' => $project_state,
                'search' => $request
            ]);
        } else {
            // Agregar relaciones específicas para materiales
            $query->with(['huawei_entry_detail.huawei_material'])
            ->whereHas('huawei_entry_detail', function ($query) use ($searchTerm) {
                $query->whereNotNull('huawei_material_id')
                      ->whereNull('huawei_equipment_serie_id')
                      ->whereHas('huawei_material', function ($query) use ($searchTerm) {
                          $query->where('name', 'like', '%'.$searchTerm.'%');
                      });
            });
            $resources = $query->get();
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
                'huawei_project' => $huawei_project,
                'huawei_project_name_code' => $huawei_project_name_code,
                'project_state' => $project_state,
                'search' => $request
            ]);
        }
    }

    public function storeProjectResource ($huawei_project, Request $request, $equipment = null)
    {
        $found_project = HuaweiProject::find($huawei_project);
        $found_detail = HuaweiEntryDetail::find($request->huawei_entry_detail_id);

        if (!$found_project->status || !$found_project->pre_report){
            abort(403, 'Acción no permitida');
        }

        if ($equipment){

            $request->validate([
                'huawei_entry_detail_id' => 'required',
            ]);

            if ($found_detail->assigned_diu !== $found_project->assigned_diu){
                abort(403, 'Equipo no asignado al proyecto');
            }

            HuaweiProjectResource::create([
                'huawei_project_id' => $huawei_project,
                'huawei_entry_detail_id' => $request->huawei_entry_detail_id,
                'quantity' => 1
            ]);

        } else{
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
            ]);


            HuaweiProjectResource::create([
                'huawei_project_id' => $huawei_project,
                'huawei_entry_detail_id' => $request->huawei_entry_detail_id,
                'quantity' => $request->quantity
            ]);
        }

        return redirect()->back();
    }

    public function refundResource (HuaweiProjectResource $huawei_resource, Request $request, $equipment = null)
    {
        $found_project = HuaweiProject::find($huawei_resource->huawei_project_id);

        if (!$found_project->status || !$found_project->pre_report){
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

        if ($equipment){
            $huawei_resource->update([
                'quantity' => 0
            ]);
        }else{
            $huawei_resource->update([
                'quantity' => $huawei_resource->quantity - $request->quantity
            ]);
        }

        return redirect()->back();
    }

    public function geResourcesToLiquidate ($huawei_project)
    {
        $found_project = HuaweiProject::find($huawei_project);

        if (!$found_project->pre_report){
            abort(403, 'Acción no permitida');
        }

        $equipments = HuaweiProjectResource::where('huawei_project_id', $huawei_project)
            ->where('quantity', '!=', 0)
            ->whereDoesntHave('huawei_project_liquidation')
            ->whereHas('huawei_entry_detail', function ($query) {
                $query->whereNull('huawei_material_id');
            })
            ->with('huawei_entry_detail.huawei_equipment_serie.huawei_equipment')
            ->paginate(10);

        $materials = HuaweiProjectResource::where('huawei_project_id', $huawei_project)
            ->where('quantity', '!=', 0)
            ->whereDoesntHave('huawei_project_liquidation')
            ->whereHas('huawei_entry_detail', function ($query) {
                $query->whereNull('huawei_equipment_serie_id');
            })
            ->with('huawei_entry_detail.huawei_material')
            ->paginate(10);

        return Inertia::render('Huawei/Liquidations', [
            'equipments' => $equipments,
            'materials' => $materials,
            'huawei_project' => $huawei_project
        ]);
    }

    public function liquidate (HuaweiProject $huawei_project, Request $request, $equipment = null) {

        if (!$huawei_project->status || !$huawei_project->pre_report){
            abort(403, 'Acción no permitida');
        }

        $huawei_project_resource = HuaweiProjectResource::with('huawei_project_liquidation')->find($request->huawei_project_resource_id);

        if (!$huawei_project_resource || $huawei_project_resource->quantity <= 0 || $huawei_project_resource->huawei_project_liquidation !== null) {
            abort(403, 'No se puede liquidar este recurso debido a restricciones.');
        }

        $data = $request->validate([
            'huawei_project_resource_id' => 'required',
            'instalation_date' => 'required',
            'liquidated_quantity' => [
                'nullable',
                function ($attribute, $value, $fail) use ($huawei_project_resource) {
                    if ($value !== null && $value > $huawei_project_resource->quantity) {
                        $fail('La cantidad liquidada debe ser menor o igual a la cantidad asignada del recurso.');
                    }
                },
            ],
        ]);

        if ($equipment){
            HuaweiProjectLiquidation::create([
                'huawei_project_resource_id' => $request->huawei_project_resource_id,
                'liquidated_quantity' => 1
            ]);
        }else{
            HuaweiProjectLiquidation::create([
                'huawei_project_resource_id' => $request->huawei_project_resource_id,
                'liquidated_quantity' => $request->liquidated_quantity
            ]);
        }
    }

    public function liquidationsHistory ($huawei_project, $equipment = null)
    {
        $found_project = HuaweiProject::find($huawei_project);

        if (!$found_project->pre_report){
            abort(403, 'Acción no permitida');
        }

        if ($equipment){
            $liquidations = HuaweiProjectLiquidation::whereHas('huawei_project_resource.huawei_entry_detail', function ($query) {
                $query->whereNull('huawei_material_id');
            })
            ->whereHas('huawei_project_resource', function ($query) use ($huawei_project) {
                $query->where('huawei_project_id', $huawei_project);
            })
            ->with('huawei_project_resource.huawei_entry_detail.huawei_equipment_serie.huawei_equipment')
            ->paginate(10);
        }else{
            $liquidations = HuaweiProjectLiquidation::whereHas('huawei_project_resource.huawei_entry_detail', function ($query) {
                $query->whereNull('huawei_equipment_serie_id');
            })
            ->whereHas('huawei_project_resource', function ($query) use ($huawei_project) {
                $query->where('huawei_project_id', $huawei_project);
            })
            ->with('huawei_project_resource.huawei_entry_detail.huawei_material')
            ->paginate(10);
        }

        return Inertia::render('Huawei/LiquidationsHistory', [
            'liquidations' => $liquidations,
            'huawei_project' => $huawei_project,
            'equipment' => $equipment
        ]);
    }

    //Earnings

    public function getEarnings (HuaweiProject $huawei_project)
    {
        if (!$huawei_project->pre_report) {
            return abort(403, 'Acción no permitida');
        }

        $earnings = HuaweiProjectEarning::where('huawei_project_id', $huawei_project->id)->orderBy('updated_at', 'desc')->paginate(10);
        $total = HuaweiProjectEarning::where('huawei_project_id', $huawei_project->id)->get()->reduce(function ($carry, $item) {
            return $carry + ($item->unit_price * $item->quantity);
        }, 0);

        return Inertia::render('Huawei/ProjectEarnings', [
            'earnings' => $earnings,
            'total' => $total,
            'huawei_project' => $huawei_project
        ]);
    }

    public function searchEarnings (HuaweiProject $huawei_project, $request)
    {
        if (!$huawei_project->pre_report) {
            return abort(403, 'Acción no permitida');
        }

        $searchTerm = strtolower($request);
        $query = HuaweiProjectEarning::where('huawei_project_id', $huawei_project->id);

            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(description) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(code) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(unit_price) LIKE ?', ["%{$searchTerm}%"]);
            });

        $earnings = $query->orderBy('updated_at', 'desc')->get();
        $total = $earnings->reduce(function ($carry, $item) {
            return $carry + ($item->unit_price * $item->quantity);
        }, 0);

        return Inertia::render('Huawei/ProjectEarnings', [
            'earnings' => $earnings,
            'huawei_project' => $huawei_project,
            'search' => $request,
            'total' => $total
        ]);
    }

    public function storeEarning (Request $request)
    {
        $found_project = HuaweiProject::find($request->huawei_project_id);

        if (!$found_project->pre_report || !$found_project->status) {
            return abort(403, 'Acción no permitida');
        }

        $data = $request->validate([
            'description' => 'required',
            'quantity' => 'required',
            'unit_price' => 'required',
            'huawei_project_id' => 'required'
        ]);

        $count = HuaweiProjectEarning::where('huawei_project_id', $request->huawei_project_id)->count();
        $nextNumber = $count + 1;
        $data['code'] = sprintf('COE-%04d', $nextNumber);
        HuaweiProjectEarning::create($data);
        return redirect()->back();
    }

    public function updateEarning (HuaweiProjectEarning $huawei_project_earning, Request $request)
    {
        $found_project = HuaweiProject::find($request->huawei_project_id);

        if (!$found_project->pre_report || !$found_project->status) {
            return abort(403, 'Acción no permitida');
        }

        $data = $request->validate([
            'description' => 'required',
            'quantity' => 'required',
            'unit_price' => 'required',
            'huawei_project_id' => 'required'
        ]);

        $huawei_project_earning->update($data);

        return redirect()->back();
    }

    public function deleteEarning (HuaweiProjectEarning $huawei_project_earning)
    {
        $found_project = HuaweiProject::find($huawei_project_earning->huawei_project_id);

        if (!$found_project->pre_report || !$found_project->status) {
            return abort(403, 'Acción no permitida');
        }

        $huawei_project_earning->delete();
        return redirect()->back();
    }

    public function importEarnings ($huawei_project, Request $request)
    {
        $found_project = HuaweiProject::find($huawei_project);

        if (!$found_project->pre_report || !$found_project->status) {
            return abort(403, 'Acción no permitida');
        }

        // Validar que el archivo es un Excel
        $data = $request->validate([
            'file' => 'required|mimes:xls,xlsx',
            'zone' => 'required'
        ]);

        // Manejar la carga del archivo
        $document = $request->file('file');

        // Leer el archivo Excel directamente desde el stream
        $spreadsheet = IOFactory::load($document->getRealPath());

        // Obtener la primera hoja
        /** @var Worksheet $sheet */
        $sheet = $spreadsheet->getSheet(0);

        // Definir el rango de lectura: A1 hasta la última fila en la columna C
        $startCell = 'A1';
        $endCell = 'C' . $sheet->getHighestRow();
        $range = "$startCell:$endCell";

        // Leer el rango especificado
        $data = $sheet->rangeToArray($range, null, true, true, true);

        // Array para almacenar los objetos
        $rowsAsObjects = [];

        // Recorrer las filas y convertir a objetos
        foreach ($data as $index => $row) {
            // Saltar la primera fila si es el encabezado
            if ($index == 1) continue;

            $rowObject = (object)[
                'code' => $row['A'],
                'description' => $row['B'],
                'quantity' => $row['C'],
            ];

            $rowsAsObjects[] = $rowObject;
        }

        foreach ($rowsAsObjects as $item) {
            $priceGuide = HuaweiPriceGuide::where('code', $item->code)->first();
            if ($priceGuide) {
                $unitPriceField = 'b' . $request->zone;
                $unitPrice = $priceGuide->$unitPriceField;

                HuaweiProjectEarning::create([
                    'code' => $item->code,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'unit_price' => $unitPrice,
                    'huawei_project_id' => $huawei_project
                ]);
            }
        }

        return redirect()->back();
    }

    public function exportEarnings (HuaweiProject $huawei_project)
    {
        return Excel::download(new HuaweiProjectEarningsExport($huawei_project->id), 'Trabajos_de_'. $huawei_project->assigned_diu .'.xlsx');
    }
}
