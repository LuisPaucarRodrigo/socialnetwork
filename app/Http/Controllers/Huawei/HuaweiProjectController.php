<?php

namespace App\Http\Controllers\Huawei;

use App\Exports\HuaweiAdditionalCostExport;
use App\Exports\HuaweiProjectEarningsExport;
use App\Exports\HuaweiStaticCostExport;
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
use App\Models\HuaweiProjectRealEarning;
use App\Models\HuaweiStaticCost;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class HuaweiProjectController extends Controller
{
    public function show ()
    {
        return Inertia::render('Huawei/Projects', [
            'projects' => HuaweiProject::where('status', 1)->with('huawei_site')->orderBy('created_at', 'desc')->paginate(10),
        ]);
    }

    public function projectHistory ()
    {
        return Inertia::render('Huawei/ProjectsHistory', [
            'projects' => HuaweiProject::where('status', 0)->with('huawei_site')->orderBy('created_at', 'desc')->paginate(10),
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
        })->with('huawei_site')->orderBy('created_at', 'desc')->get();

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
        })->with('huawei_site')->orderBy('created_at', 'desc')->get();

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
        if (!$huawei_project->status || !$huawei_project->pre_report){
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
        $projects = HuaweiProject::whereNull('status')->with('huawei_site')->orderBy('created_at', 'desc')->paginate(10);
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
        })->with('huawei_site')->orderBy('created_at', 'desc')->get();

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
            'initial_amount' => 'nullable',
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
                    'cost' => $employeeData['cost']
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
            'initial_amount' => 'nullable',
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

        $employeeId = $request->employee['id'];

        // Verifica si el empleado ya está asociado al proyecto
        if ($huawei_project->huawei_project_employees->contains('employee_id', $employeeId)) {
            abort(403, 'El empleado ya está asociado a este proyecto');
        }

        HuaweiProjectEmployee::create([
            'huawei_project_id' => $huawei_project->id,
            'employee_id' => $request->employee['id'],
            'role' => $request->charge,
            'cost' => $request->cost

        ]);
        return redirect()->back();
    }

    public function edit_employee (HuaweiProject $huawei_project, HuaweiProjectEmployee $id, Request $request)
    {
        if (!$huawei_project->status){
            abort(403, 'Acción no permitida');
        }

        $request->validate([
            'charge' => 'required',
            'cost' => 'required'
        ]);

        $id->update([
            'role' => $request->charge,
            'cost' => $request->cost
        ]);
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

    public function getCostSummary (HuaweiProject $huawei_project)
    {
        $additionalCosts = $huawei_project->huawei_additional_costs->sum('amount');
        $acArr = $huawei_project->huawei_additional_costs()
            ->select('expense_type', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('expense_type')
            ->get();
        $acExpensesAmounts = $acArr->map(function($cost){
            return [
                'expense_type' => $cost->expense_type,
                'total_amount' => $cost->total_amount
            ];
        })->toArray();


        $staticCosts = $huawei_project->huawei_static_costs->sum('amount');
        $scArr = $huawei_project->huawei_static_costs()
            ->select('expense_type', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('expense_type')
            ->get();
        $scExpensesAmounts = $scArr->map(function($cost) {
            return [
                'expense_type' => $cost->expense_type,
                'total_amount' => $cost->total_amount,
            ];
        })->toArray();

        return Inertia::render('Huawei/CostSummary', [
            'huawei_project' => $huawei_project,
            'additionalCosts' => $additionalCosts,
            'acExpensesAmounts' => $acExpensesAmounts,
            'scExpensesAmounts' => $scExpensesAmounts,
            'staticCosts' => $staticCosts,
        ]);
    }

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
        $found_project = HuaweiProject::find($huawei_project);

        if (!$found_project->status){
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
            'archive' => 'nullable|mimes:pdf'
        ]);

        $documentName = null;
        if ($request->hasFile('archive')){
            $document = $request->file('archive');
            $documentName = time() . '_' . $document->getClientOriginalName();
            $document->move(public_path('documents/huawei/additional_costs'), $documentName);
        }

        HuaweiAdditionalCost::create([
            'expense_type' => $request->expense_type,
            'ruc' => $request->ruc,
            'zone' => $request->zone,
            'type_doc' => $request->type_doc,
            'doc_number' => $request->doc_number,
            'doc_date' => $request->doc_date,
            'amount' => $request->amount,
            'description' => $request->description,
            'huawei_project_id' => $huawei_project,
            'archive' => $documentName
        ]);

        return redirect()->back();
    }

    public function updateAdditionalCosts ($huawei_project, HuaweiAdditionalCost $huawei_additional_cost, Request $request)
    {
        $found_project = HuaweiProject::find($huawei_project);

        if (!$found_project->status){
            abort(403, 'Acción no permitida');
        }

        $request->validate([
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
            'archive' => 'nullable|mimes:pdf'
        ]);

        $documentName = null;
        if ($request->hasFile('archive')){
            if ($huawei_additional_cost->archive){
                $fileName = $huawei_additional_cost->archive;
                $filePath = "documents/huawei/additional_costs/$fileName";
                $path = public_path($filePath);
                if (file_exists($path)){
                    unlink($path);
                }
            }
            $document = $request->file('archive');
            $documentName = time() . '_' . $document->getClientOriginalName();
            $document->move(public_path('documents/huawei/additional_costs'), $documentName);
        }

        $huawei_additional_cost->update([
            'expense_type' => $request->expense_type,
            'ruc' => $request->ruc,
            'zone' => $request->zone,
            'type_doc' => $request->type_doc,
            'doc_number' => $request->doc_number,
            'doc_date' => $request->doc_date,
            'amount' => $request->amount,
            'description' => $request->description,
            'huawei_project_id' => $huawei_project,
            'archive' => $request->hasFile('archive') ? $documentName : $huawei_additional_cost->archive
        ]);

        return redirect()->back();
    }

    public function search_costs (Request $request, $huawei_project_id)
    {
        $result = HuaweiAdditionalCost::where('huawei_project_id', $huawei_project_id);

        if (count($request->selectedZones) < 5) {
            $result = $result->whereIn('zone', $request->selectedZones);
        }
        if (count($request->selectedExpenseTypes) < 11) {
            $result = $result->whereIn('expense_type', $request->selectedExpenseTypes);
        }
        if (count($request->selectedDocTypes) < 5) {
            $result = $result->whereIn('type_doc', $request->selectedDocTypes);
        }
        if ($request->search) {
            $searchTerms = $request->input('search');
            $result = $result->where(function($query) use ($searchTerms){
                $query->where('ruc', 'like', "%$searchTerms%")
                ->orWhere('doc_date', 'like', "%$searchTerms%")
                ->orWhere('description', 'like', "%$searchTerms%")
                ->orWhere('amount', 'like', "%$searchTerms%");
            });
        }
        $result = $result->get();
        return response()->json($result, 200);
    }

    public function deleteAdditionalCost ($huawei_project, HuaweiAdditionalCost $huawei_additional_cost)
    {
        $found_project = HuaweiProject::find($huawei_project);

        if (!$found_project->status){
            abort(403, 'Acción no permitida');
        }

        if ($huawei_additional_cost->archive){
            $fileName = $huawei_additional_cost->archive;
            $filePath = "documents/huawei/additional_costs/$fileName";
            $path = public_path($filePath);
            if (file_exists($path)){
                unlink($path);
            }
        }

        $huawei_additional_cost->delete();
        return redirect()->back();
    }

    public function showAdditionalArchive (HuaweiAdditionalCost $huawei_additional_cost)
    {
        if ($huawei_additional_cost->archive){
            $fileName = $huawei_additional_cost->archive;
            $filePath = '/documents/huawei/additional_costs/' . $fileName;
            $path = public_path($filePath);
            if (file_exists($path)) {
                ob_end_clean();
                return response()->file($path);
            }
        }else{
            abort(403, 'Este registro no tiene archivo');
        }
    }

    public function searchAdditionalCosts (HuaweiProject $huawei_project, $request)
    {
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

    public function exportAdditionalCosts (HuaweiProject $huawei_project)
    {
        return Excel::download(new HuaweiAdditionalCostExport($huawei_project->id), 'Gastos Variables de '. $huawei_project->assigned_diu .'.xlsx');
    }

    //static costs

    public function getStaticCosts (HuaweiProject $huawei_project)
    {
        $additional_costs = HuaweiStaticCost::where('huawei_project_id', $huawei_project->id)->paginate(10);
        return Inertia::render('Huawei/StaticCosts', [
            'additional_costs' => $additional_costs,
            'huawei_project' => $huawei_project
        ]);
    }

    public function storeStaticCosts ($huawei_project, Request $request)
    {
        $found_project = HuaweiProject::find($huawei_project);

        if (!$found_project->status){
            abort(403, 'Acción no permitida');
        }

        $request->validate([
            'expense_type' => 'required',
            'ruc' => [
                'required',
                'digits:11',
                Rule::unique('huawei_static_costs')->where(function ($query) use ($huawei_project) {
                    return $query->where('huawei_project_id', $huawei_project);
                })
            ],
            'zone' => 'required',
            'type_doc' => 'required',
            'doc_number' => [
                'required',
                Rule::unique('huawei_static_costs')->where(function ($query) use ($huawei_project) {
                    return $query->where('huawei_project_id', $huawei_project);
                })
            ],
            'doc_date' => 'required',
            'amount' => 'required',
            'description' => 'nullable',
            'archive' => 'nullable|mimes:pdf'
        ]);

        $documentName = null;
        if ($request->hasFile('archive')){
            $document = $request->file('archive');
            $documentName = time() . '_' . $document->getClientOriginalName();
            $document->move(public_path('documents/huawei/static_costs'), $documentName);
        }

        HuaweiStaticCost::create([
            'expense_type' => $request->expense_type,
            'ruc' => $request->ruc,
            'zone' => $request->zone,
            'type_doc' => $request->type_doc,
            'doc_number' => $request->doc_number,
            'doc_date' => $request->doc_date,
            'amount' => $request->amount,
            'description' => $request->description,
            'huawei_project_id' => $huawei_project,
            'archive' => $documentName
        ]);

        return redirect()->back();
    }

    public function updateStaticCosts ($huawei_project, HuaweiStaticCost $static_additional_cost, Request $request)
    {
        $found_project = HuaweiProject::find($huawei_project);

        if (!$found_project->status){
            abort(403, 'Acción no permitida');
        }

        $request->validate([
            'expense_type' => 'required',
            'ruc' => [
                'required',
                'digits:11',
                Rule::unique('huawei_static_costs')->ignore($static_additional_cost->id)->where(function ($query) use ($huawei_project) {
                    return $query->where('huawei_project_id', $huawei_project);
                })
            ],
            'zone' => 'required',
            'type_doc' => 'required',
            'ruc' => [
                'required',
                Rule::unique('huawei_static_costs')->ignore($static_additional_cost->id)->where(function ($query) use ($huawei_project) {
                    return $query->where('huawei_project_id', $huawei_project);
                })
            ],
            'doc_date' => 'required',
            'amount' => 'required',
            'description' => 'nullable',
            'archive' => 'nullable|mimes:pdf'
        ]);

        $documentName = null;
        if ($request->hasFile('archive')){
            if ($static_additional_cost->archive){
                $fileName = $static_additional_cost->archive;
                $filePath = "documents/huawei/static_costs/$fileName";
                $path = public_path($filePath);
                if (file_exists($path)){
                    unlink($path);
                }
            }
            $document = $request->file('archive');
            $documentName = time() . '_' . $document->getClientOriginalName();
            $document->move(public_path('documents/huawei/static_costs'), $documentName);
        }

        $static_additional_cost->update([
            'expense_type' => $request->expense_type,
            'ruc' => $request->ruc,
            'zone' => $request->zone,
            'type_doc' => $request->type_doc,
            'doc_number' => $request->doc_number,
            'doc_date' => $request->doc_date,
            'amount' => $request->amount,
            'description' => $request->description,
            'huawei_project_id' => $huawei_project,
            'archive' => $request->hasFile('archive') ? $documentName : $static_additional_cost->archive
        ]);

        return redirect()->back();
    }

    public function search_static_costs (Request $request, $huawei_project_id)
    {
        $result = HuaweiStaticCost::where('huawei_project_id', $huawei_project_id);

        if (count($request->selectedZones) < 5) {
            $result = $result->whereIn('zone', $request->selectedZones);
        }
        if (count($request->selectedExpenseTypes) < 11) {
            $result = $result->whereIn('expense_type', $request->selectedExpenseTypes);
        }
        if (count($request->selectedDocTypes) < 5) {
            $result = $result->whereIn('type_doc', $request->selectedDocTypes);
        }
        if ($request->search) {
            $searchTerms = $request->input('search');
            $result = $result->where(function($query) use ($searchTerms){
                $query->where('ruc', 'like', "%$searchTerms%")
                ->orWhere('doc_date', 'like', "%$searchTerms%")
                ->orWhere('description', 'like', "%$searchTerms%")
                ->orWhere('amount', 'like', "%$searchTerms%");
            });
        }
        $result = $result->get();
        return response()->json($result, 200);
    }

    public function deleteStaticCost ($huawei_project, HuaweiStaticCost $static_additional_cost)
    {
        $found_project = HuaweiProject::find($huawei_project);

        if (!$found_project->status){
            abort(403, 'Acción no permitida');
        }

        if ($static_additional_cost->archive){
            $fileName = $static_additional_cost->archive;
            $filePath = "documents/huawei/static_costs/$fileName";
            $path = public_path($filePath);
            if (file_exists($path)){
                unlink($path);
            }
        }

        $static_additional_cost->delete();
        return redirect()->back();
    }

    public function showStaticArchive (HuaweiStaticCost $static_additional_cost)
    {
        if ($static_additional_cost->archive){
            $fileName = $static_additional_cost->archive;
            $filePath = '/documents/huawei/static_costs/' . $fileName;
            $path = public_path($filePath);
            if (file_exists($path)) {
                ob_end_clean();
                return response()->file($path);
            }
        }else{
            abort(403, 'Este registro no tiene archivo');
        }
    }

    public function searchStaticCosts (HuaweiProject $huawei_project, $request)
    {
        $searchTerm = strtolower($request);

        $query = HuaweiStaticCost::query();
        $query->where('huawei_project_id', $huawei_project->id)->where(function ($query) use ($searchTerm) {
            $query->where('expense_type', 'like', '%' . $searchTerm . '%')
                  ->orWhere('ruc', 'like', '%' . $searchTerm . '%')
                  ->orWhere('zone', 'like', '%' . $searchTerm . '%')
                  ->orWhere('type_doc', 'like', '%' . $searchTerm . '%')
                  ->orWhere('doc_number', 'like', '%' . $searchTerm . '%');
        });
        $filteredAdditionalCosts = $query->get();
        return Inertia::render('Huawei/StaticCosts', [
            'additional_costs' => $filteredAdditionalCosts,
            'huawei_project' => $huawei_project,
            'search' => $request
        ]);
    }

    public function exportStaticCosts (HuaweiProject $huawei_project)
    {
        return Excel::download(new HuaweiStaticCostExport($huawei_project->id), 'Gastos Fijos de '. $huawei_project->assigned_diu .'.xlsx');
    }

    //resources

    public function getResources($huawei_project, $equipment = null)
    {
        $found_project = HuaweiProject::find($huawei_project);

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


                $equipments = HuaweiEquipment::with(['huawei_equipment_series.huawei_entry_detail' => function ($query) use ($found_project) {
                    $query->where('assigned_diu', $found_project->assigned_diu);
                }])->get();

                $filteredEquipments = $equipments->filter(function ($equipment) {
                    // Verifica la relación `huawei_equipment_series`
                    if ($equipment->huawei_equipment_series) {
                        foreach ($equipment->huawei_equipment_series as $serie) {
                            // Verifica la relación `huawei_entry_detail`
                            if ($serie->huawei_entry_detail) {
                                if ($serie->huawei_entry_detail->state === 'Disponible'){
                                    return true;
                                }
                            }
                        }
                    }
                    return false;
                });

            return Inertia::render('Huawei/Resources', [
                'resources' => $resources,
                'equipment' => $equipment,
                'equipments' => $filteredEquipments,
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
                $equipments = HuaweiEquipment::with(['huawei_equipment_series.huawei_entry_detail' => function ($query) use ($found_project) {
                    $query->where('assigned_diu', $found_project->assigned_diu);
                }])->get();

                $filteredEquipments = $equipments->filter(function ($equipment) {
                    // Verifica la relación `huawei_equipment_series`
                    if ($equipment->huawei_equipment_series) {
                        foreach ($equipment->huawei_equipment_series as $serie) {
                            // Verifica la relación `huawei_entry_detail`
                            if ($serie->huawei_entry_detail) {
                                if ($serie->huawei_entry_detail->state === 'Disponible'){
                                    return true;
                                }
                            }
                        }
                    }
                    return false;
                });
            return Inertia::render('Huawei/Resources', [
                'resources' => $resources,
                'equipment' => $equipment,
                'equipments' => $filteredEquipments,
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

        if (!$found_project->status){
            abort(403, 'Acción no permitida');
        }

        if ($equipment){

            $request->validate([
                'huawei_entry_detail_ids' => 'required',
            ]);

            DB::beginTransaction();

            foreach ($request->huawei_entry_detail_ids as $detail){
                $foundDetail = HuaweiEntryDetail::find($detail);
                if ($foundDetail->assigned_diu !== $found_project->assigned_diu){
                    DB::rollBack();
                    abort(403, 'Acción no permitida.');
                }
                HuaweiProjectResource::create([
                    'huawei_project_id' => $huawei_project,
                    'huawei_entry_detail_id' => $detail,
                    'quantity' => 1
                ]);
            }

            DB::commit();

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

        if (!$found_project->status){
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
        $project = HuaweiProject::find($huawei_project);
        $project_name = $project->name . ' / ' . $project->code;
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
            'huawei_project' => $huawei_project,
            'project_name' => $project_name
        ]);
    }

    public function liquidate (HuaweiProject $huawei_project, Request $request, $equipment = null) {

        if (!$huawei_project->status){
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

        if ($equipment){
            HuaweiProjectLiquidation::create([
                'huawei_project_resource_id' => $request->huawei_project_resource_id,
                'instalation_date' => $request->instalation_date,
                'liquidated_quantity' => 1
            ]);
        }else{
            HuaweiProjectLiquidation::create([
                'huawei_project_resource_id' => $request->huawei_project_resource_id,
                'instalation_date' => $request->instalation_date,
                'liquidated_quantity' => $request->liquidated_quantity
            ]);
        }
    }

    public function liquidationsHistory ($huawei_project, $equipment = null)
    {
        $project = HuaweiProject::find($huawei_project);
        $project_name = $project->name . ' / ' . $project->code;
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
            'equipment' => $equipment,
            'project_name' => $project_name
        ]);
    }

    //Earnings

    public function getEarnings (HuaweiProject $huawei_project)
    {
        $earnings = HuaweiProjectEarning::where('huawei_project_id', $huawei_project->id)->orderBy('created_at', 'desc')->paginate(10);
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

        if (!$found_project->status) {
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

        if (!$found_project->status) {
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

        if (!$found_project->status) {
            return abort(403, 'Acción no permitida');
        }

        $huawei_project_earning->delete();
        return redirect()->back();
    }

    public function importEarnings ($huawei_project, Request $request)
    {
        $found_project = HuaweiProject::find($huawei_project);

        if (!$found_project->status) {
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
            }else{
                HuaweiProjectEarning::create([
                    'code' => $item->code,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
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

    //real_earnings

    public function getRealEarnings (HuaweiProject $huawei_project)
    {
        $real_earnings = HuaweiProjectRealEarning::where('huawei_project_id', $huawei_project->id)->orderBy('created_at', 'desc')->paginate(10);
        $total = HuaweiProjectRealEarning::where('huawei_project_id', $huawei_project->id)->get()->reduce(function ($carry, $item) {
            return $carry + ($item->amount);
        }, 0);

        return Inertia::render('Huawei/ProjectRealEarnings', [
            'real_earnings' => $real_earnings,
            'total' => $total,
            'huawei_project' => $huawei_project
        ]);
    }

    public function searchRealEarnings (HuaweiProject $huawei_project, $request)
    {
        $searchTerm = strtolower($request);
        $query = HuaweiProjectRealEarning::where('huawei_project_id', $huawei_project->id);

            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(invoice_number) LIKE ?', ["%{$searchTerm}%"]);
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

    public function storeRealEarning (Request $request)
    {
        $found_project = HuaweiProject::find($request->huawei_project_id);

        if (!$found_project->status) {
            return abort(403, 'Acción no permitida');
        }

        $data = $request->validate([
            'invoice_number' => 'required',
            'amount' => 'required',
            'invoice_date' => 'required',
            'deposit_date' => 'required'
        ]);

        $data['huawei_project_id'] = $request->huawei_project_id;
        HuaweiProjectRealEarning::create($data);
        return redirect()->back();
    }

    public function updateRealEarning (HuaweiProjectRealEarning $huawei_project_real_earning, Request $request)
    {
        $found_project = HuaweiProject::find($request->huawei_project_id);

        if (!$found_project->status) {
            return abort(403, 'Acción no permitida');
        }

        $data = $request->validate([
            'invoice_number' => 'required',
            'amount' => 'required',
            'invoice_date' => 'required',
            'deposit_date' => 'required'
        ]);

        $huawei_project_real_earning->update($data);

        return redirect()->back();
    }

    public function deleteRealEarning (HuaweiProjectRealEarning $huawei_project_real_earning)
    {
        $found_project = HuaweiProject::find($huawei_project_real_earning->huawei_project_id);

        if (!$found_project->status) {
            return abort(403, 'Acción no permitida');
        }

        $huawei_project_real_earning->delete();
        return redirect()->back();
    }
}
