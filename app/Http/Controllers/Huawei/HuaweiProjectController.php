<?php

namespace App\Http\Controllers\Huawei;

use App\Exports\HuaweiAdditionalCostExport;
use App\Exports\HuaweiProjectEarningsExport;
use App\Exports\HuaweiProjectRealEarningsExport;
use App\Exports\HuaweiStaticCostExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Huawei\HuaweiAdditionalRequest;
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
use Carbon\Carbon;

class HuaweiProjectController extends Controller
{
    public function show ($status, $prefix)
    {
        $state = null;
        switch ($status){
            case '1':
                $state = 1;
                break;
            case '2':
                $state = null;
                break;
            case '3':
                $state = 0;
                break;
            default:
                abort(403, 'Acción no permitida');
        }

        $projects = HuaweiProject::where('status', $state)
            ->where('prefix', $prefix)
            ->with('huawei_site') // Incluye la relación deseada
            ->orderBy('created_at', 'desc')
            ->paginate(10);

            $projects->getCollection()->transform(function ($project) {
                return $project->makeHidden([
                    'huawei_additional_costs',    // Reemplaza con los nombres de los campos que deseas ocultar
                    'huawei_project_earnings',
                    'huawei_project_employees',  // Reemplaza con los nombres de las relaciones que deseas ocultar
                    'huawei_project_resources',
                    'huawei_static_costs'
                ])->setRelation('huawei_site', $project->huawei_site->makeHidden([
                    'huawei_project',
                ]));
            });

        return Inertia::render('Huawei/Projects', [
            'projects' => $projects,
            'prefix' => $prefix,
            'status' => $status
        ]);
    }

    public function searchProject ($status, $prefix, $request)
    {
        $state = null;
        switch ($status){
            case '1':
                $state = 1;
                break;
            case '2':
                $state = null;
                break;
            case '3':
                $state = 0;
                break;
            default:
                abort(403, 'Acción no permitida');
        }

        $searchTerm = strtolower($request);
        $projects = HuaweiProject::where('status', $state)->where('prefix', $prefix)->where(function ($query) use ($searchTerm) {
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
            'prefix' => $prefix,
            'status' => $status
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
            'prefix' => 'required',
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
                'prefix' => $request->prefix,
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
                'prefix' => $request->prefix,
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
        $additional_costs = HuaweiAdditionalCost::where('huawei_project_id', $huawei_project->id)->orderBy('created_at', 'desc')->paginate(10);
        return Inertia::render('Huawei/AdditionalCosts', [
            'expense' => $additional_costs,
            'project' => $huawei_project
        ]);
    }

    public function storeAdditionalCosts ($huawei_project, HuaweiAdditionalRequest $request)
    {
        $found_project = HuaweiProject::find($huawei_project);

        if (!$found_project->status){
            abort(403, 'Acción no permitida');
        }

        $data = $request->validated();
        $data['huawei_project_id'] = $huawei_project;
        $expense = HuaweiAdditionalCost::create($data);

        $expenseDirectory = 'documents/huawei/additional_costs/';
        $imageFields = ['image1', 'image2', 'image3'];
        $imageUpdates = [];

        foreach ($imageFields as $index => $field) {
            if ($request->hasFile($field)) {
                $image = $request->file($field);
                $filename = "expense_{$expense->id}_" . ($index + 1) . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path($expenseDirectory), $filename);
                $imageUpdates[$field] = $filename;
            } else {
                $imageUpdates[$field] = null;
            }
        }

        $expense->update($imageUpdates);

        return redirect()->back();
    }

    public function updateAdditionalCosts ($huawei_project, HuaweiAdditionalCost $huawei_additional_cost, HuaweiAdditionalRequest $request)
    {
        $data = $request->validated();

        $expenseDirectory = 'documents/huawei/additional_costs/';

        $imageFields = ['image1', 'image2', 'image3'];

        foreach ($imageFields as $index => $field) {
            if ($request->hasFile($field)) {
                if ($huawei_additional_cost->$field) {
                    $oldPath = public_path($expenseDirectory . $huawei_additional_cost->$field);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                $imageFile = $request->file($field);
                $filename = "expense_{$huawei_additional_cost->id}_" . ($index + 1) . '_' . time() . '.' . $imageFile->getClientOriginalExtension();
                $imageFile->move(public_path('documents/huawei/additional_costs/'), $filename);
                $data[$field] = $filename;
            } else {
                $data[$field] = $huawei_additional_cost->$field;
            }
        }

        $huawei_additional_cost->update($data);

        return redirect()->back()->with('success', 'Gasto actualizado correctamente y archivos procesados.');
    }


    public function showImage (HuaweiAdditionalCost $expense, $image)
    {
        $field = 'image' . $image;
        $imageToShow = $expense->$field;
        $path = public_path("documents/huawei/additional_costs/$imageToShow");
        if (file_exists($path)){
            ob_end_clean();
            return response()->file($path);
        }
        abort(403, 'Imagen no encontrada');
    }

    public function validateExpense (Request $request, HuaweiAdditionalCost $expense)
    {
        $validatedData = $request->validate([
            'is_accepted' => 'required|boolean'
        ]);

        $expense->update($validatedData);

        return response()->noContent();
    }


    public function search_costs (Request $request, $huawei_project)
    {
        $expenses = HuaweiAdditionalCost::where('huawei_project_id', $huawei_project);

        if (count($request->selectedZones) < 17) {  // Filtrar solo si hay valor
            $expenses->whereIn('zone', $request->selectedZones);
        }
        if (count($request->selectedExpenseTypes) < 9){
            $expenses->whereIn('expense_type', $request->selectedExpenseTypes);
        }
        if (count($request->selectedCDPTypes) < 7){
            $expenses->whereIn('cdp_type', $request->selectedCDPTypes);
        }
        if (count($request->selectedEmployees) < 15){
            $expenses->whereIn('employee', $request->selectedEmployees);
        }
        if($request->exStartDate){
            $expenses->where('expense_date', '>=', $request->exStartDate);
        }
        if($request->exEndDate){
            $expenses->where('expense_date', '<=', $request->exEndDate);
        }
        if($request->exNoDate){
            $expenses->where('expense_date', null);
        }
        if($request->opStartDate){
            $expenses->where('ec_expense_date', '>=', $request->opStartDate);
        }
        if($request->opEndDate){
            $expenses->where('ec_expense_date', '<=', $request->opEndDate);
        }
        if($request->opNoDate){
            $expenses->where('ec_expense_date', null);
        }
        $expenses = $expenses->orderBy('created_at', 'desc')->get(); // Asegúrate de asignar el resultado
        return response()->json(["expenses" => $expenses], 200);
    }

    public function deleteAdditionalCost ($huawei_project, HuaweiAdditionalCost $huawei_additional_cost)
    {
        $found_project = HuaweiProject::find($huawei_project);

        if (!$found_project->status){
            abort(403, 'Acción no permitida');
        }
        $expenseDirectory = 'documents/huawei/additional_costs/';
        $imageFields = ['image1', 'image2', 'image3'];

        foreach ($imageFields as $index => $field) {
            if ($huawei_additional_cost->$field) {
                $oldPath = public_path($expenseDirectory . $huawei_additional_cost->$field);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
        }

        $huawei_additional_cost->delete();
        return redirect()->back();
    }

    public function massiveUpdate (Request $request)
    {
        $data = $request->validate([
            'ids' => 'required | array | min:1',
            'ids.*' => 'integer',
            'ec_expense_date' => 'required|date',
            'ec_op_number' => 'required|min:6',
        ]);


        HuaweiAdditionalCost::whereIn('id', $data['ids'])->update([
            'ec_expense_date' => $data['ec_expense_date'],
            'ec_op_number' => $data['ec_op_number'],
        ]);

        $updatedCosts = HuaweiAdditionalCost::whereIn('id', $data['ids'])
            ->get();

        return response()->json($updatedCosts, 200);
    }

    public function searchAdditionalCosts (HuaweiProject $huawei_project, $request)
    {
        $searchTerm = strtolower($request);

        $expensesQuery = HuaweiAdditionalCost::query()
            ->where('huawei_project_id', $huawei_project->id)
            ->where(function ($query) use ($searchTerm) {
                $query->whereRaw('LOWER(expense_type) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(zone) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(employee) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(cdp_type) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(doc_number) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(op_number) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(ruc) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(description) LIKE ?', ["%{$searchTerm}%"]);
            });

        $expenses = $expensesQuery->orderBy('created_at', 'desc')->get();

        return Inertia::render('Huawei/AdditionalCosts', [
            'expense' => $expenses,
            'project' => $huawei_project,
            'search' => $request
        ]);
    }

    public function exportAdditionalCosts (HuaweiProject $huawei_project)
    {
        return Excel::download(new HuaweiAdditionalCostExport($huawei_project->id), 'Gastos Variables de '. $huawei_project->assigned_diu .'.xlsx');
    }

    public function importCosts($huawei_project, Request $request)
    {
        $found_project = HuaweiProject::find($huawei_project);

        if (!$found_project->status) {
            return abort(403, 'Acción no permitida');
        }

        // Validar que el archivo es un Excel
        $data = $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        // Manejar la carga del archivo
        $document = $request->file('file');

        // Leer el archivo Excel directamente desde el stream
        $spreadsheet = IOFactory::load($document->getRealPath());

        // Obtener la primera hoja
        /** @var Worksheet $sheet */
        $sheet = $spreadsheet->getSheet(0);

        // Definir el rango de lectura: A1 hasta la última fila en la columna D
        $startCell = 'A1';
        $endCell = 'D' . $sheet->getHighestRow();
        $range = "$startCell:$endCell";

        // Leer el rango especificado
        $data = $sheet->rangeToArray($range, null, true, true, true);

        // Array para almacenar los objetos
        $rowsAsObjects = [];

        // Recorrer las filas y convertir a objetos

        foreach ($data as $index => $row) {

            $rowObject = (object)[
                'zone' => $this->sanitizeText($row['A'], false),
                'cost_date' => $this->sanitizeDate($row['B']),
                'amount' => $this->sanitizeNumber($row['C']),
                'expense_type' => $this->sanitizeText($row['D'], true)
            ];

            $rowsAsObjects[] = $rowObject;
        }


            foreach ($rowsAsObjects as $item) {
                if (in_array(trim($item->expense_type), ['Planilla', 'Transporte', 'Fletes', 'Alimentacion', 'Consumibles', 'Hospedaje', 'Movilidad'], true)) {
                    // Insert into HuaweiStaticCost
                    HuaweiStaticCost::create([
                        'zone' => $item->zone,
                        'cost_date' => $item->cost_date,
                        'amount' => $item->amount,
                        'expense_type' => $item->expense_type,
                        'huawei_project_id' => $huawei_project
                    ]);
                } else {
                    // Insert into HuaweiAdditionalCost
                    HuaweiAdditionalCost::create([
                        'zone' => $item->zone,
                        'cost_date' => $item->cost_date,
                        'amount' => $item->amount,
                        'expense_type' => $item->expense_type,
                        'huawei_project_id' => $huawei_project
                    ]);
                }
            }


        return redirect()->back();
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

        $data = $request->validate([
            'expense_type' => 'required',
            'zone' => 'required',
            'cost_date' => 'required',
            'amount' => 'required',
        ]);

        $data['huawei_project_id'] = $huawei_project;

        HuaweiStaticCost::create($data);

        return redirect()->back();
    }

    public function updateStaticCosts ($huawei_project, HuaweiStaticCost $static_additional_cost, Request $request)
    {
        $found_project = HuaweiProject::find($huawei_project);

        if (!$found_project->status){
            abort(403, 'Acción no permitida');
        }

        $data = $request->validate([
            'expense_type' => 'required',
            'zone' => 'required',
            'cost_date' => 'required',
            'amount' => 'required',
        ]);

        $data['huawei_project_id'] = $huawei_project;

        $static_additional_cost->update($data);

        return redirect()->back();
    }

    public function search_static_costs (Request $request, $huawei_project_id)
    {
        $result = HuaweiStaticCost::where('huawei_project_id', $huawei_project_id);

        if (count($request->selectedExpenseTypes) < 8) {
            $result = $result->whereIn('expense_type', $request->selectedExpenseTypes);
        }
        if ($request->search) {
            $searchTerms = $request->input('search');
            $result = $result->where(function($query) use ($searchTerms){
                $query->where('cost_date', 'like', "%$searchTerms%")
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

        $static_additional_cost->delete();
        return redirect()->back();
    }

    public function searchStaticCosts (HuaweiProject $huawei_project, $request)
    {
        $searchTerm = strtolower($request);

        $query = HuaweiStaticCost::query();
        $query->where('huawei_project_id', $huawei_project->id)->where(function ($query) use ($searchTerm) {
            $query->where('expense_type', 'like', '%' . $searchTerm . '%')
                  ->orWhere('zone', 'like', '%' . $searchTerm . '%');
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

    private function sanitizeText($text, $mode)
    {
        if ($mode) {
            // Modo 1: Convertir a primera letra en mayúscula y el resto en minúsculas
            return ucwords(strtolower($text));
        } else {
            // Modo 2: Eliminar espacios en blanco, convertir a mayúsculas y eliminar tildes
            $text = strtoupper($text);

            // Reemplazar tildes
            $text = str_replace(
                ['Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ'],
                ['A', 'E', 'I', 'O', 'U', 'N'],
                $text
            );

            // Eliminar todos los espacios
            return preg_replace('/\s+/', '', $text);
        }
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

            foreach ($resources as $resource){
                $resource->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name = $this->sanitizeText2($resource->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name);
            }

                $equipments = HuaweiEquipment::where('prefix', $found_project->prefix)->with(['huawei_equipment_series.huawei_entry_detail' => function ($query) use ($found_project) {
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
                })
                ->map(function ($equipment) {
                    $equipment->name = $this->sanitizeText2($equipment->name); // Aplica la función de sanitización al nombre
                    return $equipment;
                })
                ->values()->toArray();

            return Inertia::render('Huawei/Resources', [
                'resources' => $resources,
                'equipment' => $equipment,
                'equipments' => $filteredEquipments,
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

            foreach ($resources as $resource){
                $resource->huawei_entry_detail->huawei_material->name = $this->sanitizeText2($resource->huawei_entry_detail->huawei_material->name);
            }

            $materials =  HuaweiMaterial::where('prefix', $found_project->prefix)
                ->get()
                ->filter(function ($material) {
                    return $material->available_quantity > 0;
                })
                ->map(function ($material) {
                    $material->name = $this->sanitizeText2($material->name); // Aplica la función de sanitización al nombre
                    return $material;
                })
                ->values()
                ->toArray();

            return Inertia::render('Huawei/Resources', [
                'resources' => $resources,
                'equipment' => $equipment,
                'materials' => $materials,
                'huawei_project' => $huawei_project,
                'huawei_project_name_code' => $huawei_project_name_code,
                'project_state' => $project_state
            ]);
        }
    }

    public function searchEntryDetails (HuaweiProject $huawei_project, $id, $equipment = null)
    {
        if ($equipment){
            $entry_details = HuaweiEntryDetail::whereNull('huawei_material_id')->where('assigned_diu', $huawei_project->assigned_diu)
                ->whereHas('huawei_equipment_serie', function ($query) use ($id) {
                    $query->where('huawei_equipment_id', $id);
                })->with(['huawei_equipment_serie' => function ($query){
                    $query->select('id', 'serie_number');
                }])->get()
                ->filter(function ($detail){
                    return $detail->state = 'Disponible';
                })->map(function ($detail){
                    return [
                        'id' => $detail->id,
                        'serie_number' => $detail->huawei_equipment_serie->serie_number,
                        'order_number' => $detail->order_number
                    ];
                });
        }else{
            $entry_details = HuaweiEntryDetail::whereNull('huawei_equipment_serie_id')->where('huawei_material_id', $id)
                ->get()
                ->filter(function ($detail){
                    return $detail->state == 'Disponible';
                })->map(function ($detail){
                    return [
                        'id' => $detail->id,
                        'order_number' => $detail->order_number,
                        'available_quantity' => $detail->available_quantity
                    ];
                });
        }
        return response()->json(['details' => $entry_details]);
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
            foreach ($resources as $resource){
                $resource->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name = $this->sanitizeText2($resource->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name);
            }
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
                })
                ->map(function ($equipment) {
                    $equipment->name = $this->sanitizeText2($equipment->name); // Aplica la función de sanitización al nombre
                    return $equipment;
                })
                ->values()->toArray();

            return Inertia::render('Huawei/Resources', [
                'resources' => $resources,
                'equipment' => $equipment,
                'equipments' => $filteredEquipments,
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
            foreach ($resources as $resource){
                $resource->huawei_entry_detail->huawei_material->name = $this->sanitizeText2($resource->huawei_entry_detail->huawei_material->name);
            }
            $materials =  HuaweiMaterial::where('prefix', $found_project->prefix)
                ->get()
                ->makeHidden(['huawei_entry_details'])
                ->filter(function ($material) {
                    return $material->available_quantity > 0;
                })
                ->map(function ($material) {
                    $material->name = $this->sanitizeText2($material->name); // Aplica la función de sanitización al nombre
                    return $material;
                })
                ->values()
                ->toArray();

            return Inertia::render('Huawei/Resources', [
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
                    'quantity' => 1,
                    'output_date' => $request->output_date
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
            ->get();

        foreach ($equipments as $resource){
            $resource->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name = $this->sanitizeText2($resource->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name);
        }

        $materials = HuaweiProjectResource::where('huawei_project_id', $huawei_project)
            ->where('quantity', '!=', 0)
            ->whereDoesntHave('huawei_project_liquidation')
            ->whereHas('huawei_entry_detail', function ($query) {
                $query->whereNull('huawei_equipment_serie_id');
            })
            ->with('huawei_entry_detail.huawei_material')
            ->get();

        foreach ($materials as $resource){
            $resource->huawei_entry_detail->huawei_material->name = $this->sanitizeText2($resource->huawei_entry_detail->huawei_material->name);
        }

        return Inertia::render('Huawei/Liquidations', [
            'equipments' => $equipments,
            'materials' => $materials,
            'huawei_project' => $huawei_project,
            'project_name' => $project_name,
            'projectState' => $project->status
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

            foreach ($liquidations as $resource){
                $resource->huawei_project_resource->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name = $this->sanitizeText2($resource->huawei_project_resource->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name);
            }

        }else{
            $liquidations = HuaweiProjectLiquidation::whereHas('huawei_project_resource.huawei_entry_detail', function ($query) {
                $query->whereNull('huawei_equipment_serie_id');
            })
            ->whereHas('huawei_project_resource', function ($query) use ($huawei_project) {
                $query->where('huawei_project_id', $huawei_project);
            })
            ->with('huawei_project_resource.huawei_entry_detail.huawei_material')
            ->paginate(10);
            foreach ($liquidations as $resource){
                $resource->huawei_project_resource->huawei_entry_detail->huawei_material->name = $this->sanitizeText2($resource->huawei_project_resource->huawei_entry_detail->huawei_material->name);
            }
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
            'huawei_project_id' => 'required',
            'state' => [
                'required',
                'string',
                'in:Pendiente,En Proceso,Completado,Cancelado'
            ],
        ]);

        $count = HuaweiProjectEarning::where('huawei_project_id', $request->huawei_project_id)->count();
        $nextNumber = $count + 1;
        $data['code'] = sprintf('COE-%04d', $nextNumber);
        HuaweiProjectEarning::create($data);
        return redirect()->back();
    }

    public function updateEarningState (HuaweiProject $huawei_project, HuaweiProjectEarning $earning, Request $request)
    {
        if (!$huawei_project->status){
            abort(403, 'Acción no permitida');
        }
        $data = $request->validate([
            'state' => 'required'
        ]);

        $earning->update($data);

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
            'huawei_project_id' => 'required',
            'state' => [
                'required',
                'string',
                'in:Pendiente,En Proceso,Completado,Cancelado'
            ],
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
        $endCell = 'D' . $sheet->getHighestRow();
        $range = "$startCell:$endCell";

        // Leer el rango especificado
        $data = $sheet->rangeToArray($range, null, true, true, true);

        // Array para almacenar los objetos
        $rowsAsObjects = [];

        // Recorrer las filas y convertir a objetos
        foreach ($data as $index => $row) {

            $rowObject = (object)[
                'code' => $row['A'],
                'description' => $row['B'],
                'quantity' => $row['C'],
                'state' => $row['D'] ? $row['D'] : 'Pendiente'
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
                    'huawei_project_id' => $huawei_project,
                    'state' => $item->state
                ]);
            }else{
                HuaweiProjectEarning::create([
                    'code' => $item->code,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'state' => $item->state,
                    'huawei_project_id' => $huawei_project
                ]);
            }
        }

        return redirect()->back();
    }

    public function exportEarnings (HuaweiProject $huawei_project)
    {
        return Excel::download(new HuaweiProjectEarningsExport($huawei_project->id), 'Ingresos proyectados de '. $huawei_project->assigned_diu .'.xlsx');
    }

    //real_earnings

    public function getRealEarnings (HuaweiProject $huawei_project)
    {
        $real_earnings = HuaweiProjectRealEarning::where('huawei_project_id', $huawei_project->id)->orderBy('created_at', 'desc')->paginate(10);
        $total = HuaweiProjectRealEarning::where('huawei_project_id', $huawei_project->id)
        ->whereNotNull('deposit_date')
        ->sum('amount');

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
                $q->whereRaw('LOWER(invoice_number) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(main_op_number) LIKE ? ', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(detraction_op_number) LIKE ? ', ["%{$searchTerm}%"]);
            });

        $earnings = $query->orderBy('created_at', 'desc')->get();
        $total = HuaweiProjectRealEarning::where('huawei_project_id', $huawei_project->id)
        ->whereNotNull('deposit_date')
        ->sum('amount');

        return Inertia::render('Huawei/ProjectRealEarnings', [
            'real_earnings' => $earnings,
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
            'deposit_date' => 'nullable',
            'main_amount' => 'nullable',
            'main_op_number' => 'nullable',
            'detraction_amount' => 'nullable',
            'detraction_date' => 'nullable',
            'detraction_op_number' => 'nullable'
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
            'deposit_date' => 'nullable',
            'main_amount' => 'nullable',
            'main_op_number' => 'nullable',
            'detraction_amount' => 'nullable',
            'detraction_date' => 'nullable',
            'detraction_op_number' => 'nullable'
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

    public function exportRealEarnings (HuaweiProject $huawei_project)
    {
        return Excel::download(new HuaweiProjectRealEarningsExport($huawei_project->id), 'Ingresos reales de '. $huawei_project->assigned_diu .'.xlsx');
    }

    public function importRealEarnings($huawei_project, Request $request)
    {
        $found_project = HuaweiProject::find($huawei_project);

        if (!$found_project->status) {
            return abort(403, 'Acción no permitida');
        }

        // Validar que el archivo es un Excel
        $data = $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        // Manejar la carga del archivo
        $document = $request->file('file');

        // Leer el archivo Excel directamente desde el stream
        $spreadsheet = IOFactory::load($document->getRealPath());

        // Obtener la primera hoja
        /** @var Worksheet $sheet */
        $sheet = $spreadsheet->getSheet(0);

        // Definir el rango de lectura: A1 hasta la última fila en la columna D
        $startCell = 'A1';
        $endCell = 'I' . $sheet->getHighestRow();
        $range = "$startCell:$endCell";

        // Leer el rango especificado
        $data = $sheet->rangeToArray($range, null, true, true, true);

        $groupedData = [];

        // Recorrer las filas y agrupar los datos
        foreach ($data as $index => $row) {
            $invoice_number = $row['A'];

            // Si no existe aún en groupedData, lo agregamos
            if (!isset($groupedData[$invoice_number])) {
                $groupedData[$invoice_number] = (object) [
                    'invoice_number' => $row['A'],
                    'amount' => $this->sanitizeNumber($row['B']),
                    'invoice_date' => $this->sanitizeDate($row['C']),
                    'deposit_date' => !empty($row['D']) ? $this->sanitizeDate($row['D']) : null,
                    'main_op_number' => !empty($row['E']) ? $row['E'] : null,
                    'main_amount' => !empty($row['F']) ? $this->sanitizeNumber($row['F']) : null,
                    'detraction_date' => !empty($row['G']) ? $this->sanitizeDate($row['G']) : null,
                    'detraction_amount' => !empty($row['H']) ? $this->sanitizeNumber($row['H']) : null,
                    'detraction_op_number' => !empty($row['I']) ? $row['I'] : null,
                ];
            } else {
                // Si ya existe, sumamos los valores de amount, main_amount y detraction_amount
                $groupedData[$invoice_number]->amount += $this->sanitizeNumber($row['B']);
                $groupedData[$invoice_number]->main_amount += !empty($row['F']) ? $this->sanitizeNumber($row['F']) : 0;
                $groupedData[$invoice_number]->detraction_amount += !empty($row['H']) ? $this->sanitizeNumber($row['H']) : 0;
            }
        }

        // Convertimos el array asociativo a un array plano con los objetos únicos por invoice_number
        $rowsAsObjects = array_values($groupedData);

        // Empezar la transacción
        DB::beginTransaction();

        try {
            foreach ($rowsAsObjects as $item) {
                $found_earning = HuaweiProjectRealEarning::where('invoice_number', $item->invoice_number)->where('huawei_project_id', $huawei_project)->first();

                if ($found_earning) {
                    // Si el registro ya existe, actualizamos
                    $found_earning->update([
                        'amount' => $item->amount,
                        'invoice_date' => $item->invoice_date,
                        'deposit_date' => $item->deposit_date,
                        'main_amount' => $item->main_amount,
                        'main_op_number' => $item->main_op_number,
                        'detraction_amount' => $item->detraction_amount,
                        'detraction_op_number' => $item->detraction_op_number,
                        'detraction_date' => $item->detraction_date,
                    ]);
                } else {
                    // Si no existe, creamos un nuevo registro
                    HuaweiProjectRealEarning::create([
                        'invoice_number' => $item->invoice_number,
                        'amount' => $item->amount,
                        'invoice_date' => $item->invoice_date,
                        'deposit_date' => $item->deposit_date,
                        'main_amount' => $item->main_amount,
                        'main_op_number' => $item->main_op_number,
                        'detraction_amount' => $item->detraction_amount,
                        'detraction_op_number' => $item->detraction_op_number,
                        'detraction_date' => $item->detraction_date,
                        'huawei_project_id' => $huawei_project
                    ]);
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['earning_error' => 'Hubo un problema al procesar los registros.'])->withInput();
        }

        DB::commit();

        return redirect()->back();
    }

    public function verifyImportRealEarnings ($huawei_project, Request $request)
    {
        $data = $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        // Manejar la carga del archivo
        $document = $request->file('file');

        // Leer el archivo Excel directamente desde el stream
        $spreadsheet = IOFactory::load($document->getRealPath());

        // Obtener la primera hoja
        /** @var Worksheet $sheet */
        $sheet = $spreadsheet->getSheet(0);

        // Definir el rango de lectura: A1 hasta la última fila en la columna D
        $startCell = 'A1';
        $endCell = 'A' . $sheet->getHighestRow();
        $range = "$startCell:$endCell";

        // Leer el rango especificado
        $data = $sheet->rangeToArray($range, null, true, true, true);

        // Array para almacenar los objetos
        $rowsAsObjects = [];

        // Recorrer las filas y convertir a objetos

        foreach ($data as $index => $row) {

            $rowObject = (object)[
                'invoice_number' => $row['A'],
            ];

            $rowsAsObjects[] = $rowObject;
        }

        foreach ($rowsAsObjects as $item) {
            $found_earning = HuaweiProjectRealEarning::where('invoice_number', $item->invoice_number)->where('huawei_project_id', $huawei_project)->first();
            if ($found_earning){
                return response()->json([
                    'message' => 'found'
                ]);
            }
        }

        return response()->json([
            'message' => 'notfound'
        ]);
    }

    //private functions


    private function sanitizeDate($date)
    {
        // Definir los formatos de fecha esperados
        $formats = [
            'd / m / Y', // Ejemplo: 01 / 01 / 2024
            'd/m/Y',     // Ejemplo: 01/01/2024
            'Y-m-d',     // Ejemplo: 2024-01-01
            'd-m-Y',     // Ejemplo: 01-01-2024
            'd.m.Y',     // Ejemplo: 01.01.2024
        ];

        // Intentar analizar la fecha con los formatos definidos
        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $date)->format('Y-m-d');
            } catch (\Exception $e) {
                // Continúa al siguiente formato si falla
                continue;
            }
        }

        // Si ninguno de los formatos funcionó, intentar un parseo general
        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            // En caso de error, puedes manejar el error o retornar un valor por defecto
            return null; // o cualquier valor por defecto que prefieras
        }
    }


    private function sanitizeNumber($text)
    {
        // Remover todos los caracteres que no sean dígitos o puntos
        $sanitized = preg_replace('/[^0-9.]/', '', $text);

        // Si hay más de un punto, remover todos menos el último
        if (substr_count($sanitized, '.') > 1) {
            $parts = explode('.', $sanitized);
            $sanitized = implode('', array_slice($parts, 0, -1)) . '.' . end($parts);
        }

        return $sanitized;
    }

    private function sanitizeText2($text) {
        // Usa una expresión regular para eliminar el texto entre paréntesis al principio del texto
        return preg_replace('/^\(.*?\)\s*/', '', $text);
    }
}
