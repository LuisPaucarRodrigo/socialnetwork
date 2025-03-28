<?php

namespace App\Http\Controllers\Huawei;

use App\Constants\HuaweiConstants;
use App\Exports\HuaweiMonthlyExport;
use App\Exports\HuaweiProjectEarningsExport;
use App\Exports\HuaweiProjectRealEarningsExport;
use App\Http\Controllers\Controller;
use App\Models\CostCenter;
use App\Models\HuaweiMonthlyExpense;
use App\Models\HuaweiProject;
use App\Models\HuaweiProjectEmployee;
use App\Models\HuaweiProjectSchedule;
use App\Models\HuaweiSite;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\HuaweiEntryDetail;
use App\Models\HuaweiEquipment;
use App\Models\HuaweiMaterial;
use App\Models\HuaweiProjectEarning;
use App\Models\HuaweiProjectLiquidation;
use App\Models\HuaweiProjectResource;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\HuaweiPriceGuide;
use App\Models\HuaweiProjectRealEarning;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HuaweiProjectController extends Controller
{
    public function show($status, $prefix)
    {
        $state = null;
        switch ($status) {
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
            ->select('id', 'name', 'assigned_diu', 'description', 'ot', 'zone', 'prefix', 'macro_project', 'huawei_site_id', 'status')
            ->where('prefix', $prefix)
            ->with('huawei_site') // Incluye la relación deseada
            ->orderBy('created_at', 'desc')
            ->get();

        $projects->transform(function ($project) {
            return $project->makeHidden([
                'huawei_additional_costs',    // Reemplaza con los nombres de los campos que deseas ocultar
                'huawei_project_earnings',
                'huawei_project_employees',  // Reemplaza con los nombres de las relaciones que deseas ocultar
                'huawei_project_resources',
                'huawei_static_costs',
                'additional_cost_total',
                'static_cost_total',
                'materials_in_project',
                'equipments_in_project',
                'materials_liquidated',
                'equipments_liquidated',
                'total_earnings',
                'total_real_earnings',
                'total_real_earnings_without_deposit',
                'total_project_cost',
                'total_employee_costs',
                'total_essalud_employee_cost'
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

    public function searchProject($status, $prefix, $request)
    {
        $state = null;
        switch ($status) {
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
        $projects = HuaweiProject::select('id', 'name', 'assigned_diu', 'description', 'ot', 'zone', 'prefix', 'macro_project', 'huawei_site_id', 'status')->where('status', $state)->where('prefix', $prefix)->where(function ($query) use ($searchTerm) {
            $query->whereRaw('LOWER(name) like ?', ['%' . $searchTerm . '%'])
                ->orWhereRaw('LOWER(description) like ?', ['%' . $searchTerm . '%'])
                ->orWhereRaw('LOWER(ot) LIKE ?', ["%{$searchTerm}%"])
                ->orWhereRaw('LOWER(zone) LIKE ?', ["%{$searchTerm}%"])
                ->orWhereRaw('LOWER(assigned_diu) LIKE ?', ["%{$searchTerm}%"])
                ->orWhereRaw('LOWER(macro_project) LIKE ?', ["%{$searchTerm}%"])
                ->orWhereHas('huawei_site', function ($query) use ($searchTerm) {
                    $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"]);
                });
        })->orderBy('created_at', 'desc')->get();

        $projects->transform(function ($project) {
            return $project->makeHidden([
                'huawei_additional_costs',    // Reemplaza con los nombres de los campos que deseas ocultar
                'huawei_project_earnings',
                'huawei_project_employees',  // Reemplaza con los nombres de las relaciones que deseas ocultar
                'huawei_project_resources',
                'huawei_static_costs',
                'additional_cost_total',
                'static_cost_total',
                'materials_in_project',
                'equipments_in_project',
                'materials_liquidated',
                'equipments_liquidated',
                'total_earnings',
                'total_real_earnings',
                'total_real_earnings_without_deposit',
                'total_project_cost',
                'total_employee_costs',
                'total_essalud_employee_cost'
            ])->setRelation('huawei_site', $project->huawei_site->makeHidden([
                            'huawei_project',
                        ]));
        });

        return Inertia::render('Huawei/Projects', [
            'projects' => $projects,
            'search' => $request,
            'prefix' => $prefix,
            'status' => $status
        ]);
    }

    public function create()
    {
        return Inertia::render('Huawei/ProjectForm', [
            'huawei_sites' => HuaweiSite::orderBy('name')->get(),
            'cost_centers' => CostCenter::where('cost_line_id', 3)->get(),
            'price_guides' => HuaweiPriceGuide::all(),
        ]);
    }

    public function importBaseLines(Request $request, $zone)
    {
        $data = $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        $document = $request->file('file');

        $spreadsheet = IOFactory::load($document->getRealPath());

        /** @var Worksheet $sheet */
        $sheet = $spreadsheet->getSheet(0);

        $startCell = 'A1';
        $endCell = 'E' . $sheet->getHighestRow();
        $range = "$startCell:$endCell";

        $data = $sheet->rangeToArray($range, null, true, true, true);

        $rowsAsObjects = [];

        foreach ($data as $index => $row) {
            if ($index === 1)
                continue;
            $isEmptyRow = empty($row['A']) && empty($row['B']) && empty($row['C']) && empty($row['D']) &&
                empty($row['E']);

            if ($isEmptyRow) {
                break;
            }
            $rowObject = (object) [
                'code' => $row['A'],
                'quantity' => $row['B'],
                'evidence' => $row['C'],
                'goal' => $row['D'],
                'observation' => $row['E'],
            ];

            $rowsAsObjects[] = $rowObject;
        }
        $response = [];

        foreach ($rowsAsObjects as $item) {
            if (empty($item->code) || (!isset($item->quantity) && $item->quantity !== 0) || empty($item->evidence) || empty($item->goal)) {
                return response()->json(['error' => 'Llene todos los campos'], 400);
            }

            $price_guide = HuaweiPriceGuide::where('code', $item->code)->first();
            if (!$price_guide) {
                return response()->json(['error' => 'Uno de los códigos no existe en la guía de precios'], 400);
            }
            $objectTo = [
                'code' => $item->code,
                'description' => $price_guide->description,
                'unit' => $price_guide->unit,
                'unit_price' => $price_guide->$zone,
                'quantity' => $item->quantity,
                'total_price' => $price_guide->$zone * $item->quantity,
                'evidence' => $item->evidence,
                'goal' => $item->goal,
                'observation' => $item->observation,
            ];

            $response[] = $objectTo;
        }

        return response()->json(["lines" => $response], 200);
    }

    public function downloadTemplate()
    {
        $templatePath = public_path('documents/huawei/resources/Data Structure - Base Lines.xlsx');
        ob_end_clean();
        return response()->download($templatePath, 'Estructura de Datos - Líneas Base Huawei.xlsx');
    }


    public function toUpdate($huawei_project)
    {
        $huawei_project = HuaweiProject::select(
            'id',
            'name',
            'huawei_site_id',
            'description',
            'ot',
            'assigned_diu',
            'assignation_date',
            'zone',
            'cost_center_id',
            'status',
            'prefix',
            'macro_project'
        )->where('id', $huawei_project)
            ->with([
                'huawei_site',
                'cost_center',
                'huawei_project_earnings',
                'huawei_project_schedules',
            ])->first()
            ->makeHidden([
                'additional_cost_total',
                'static_cost_total',
                'materials_in_project',
                'equipments_in_project',
                'materials_liquidated',
                'equipments_liquidated',
                'total_earnings',
                'total_real_earnings',
                'total_real_earnings_without_deposit',
                'total_project_cost',
                'total_employee_costs',
                'total_essalud_employee_cost',
                'huawei_project_resources'
            ]);

        if (!$huawei_project->status) {
            abort(403, 'Acción no permitida');
        }

        return Inertia::render('Huawei/ProjectForm', [
            'huawei_project' => $huawei_project,
            'huawei_sites' => HuaweiSite::orderBy('name')->get(),
        ]);
    }

    public function liquidateProject(HuaweiProject $huawei_project)
    {
        if (!$huawei_project->status || !$huawei_project->pre_report) {
            abort(403, 'Acción no permitida');
        }

        if (!$huawei_project->state) {
            abort(403, 'Proyecto no apto para liquidación');
        }

        $huawei_project->update([
            'status' => false
        ]);

        return redirect()->back();
    }

    public function cancelProject(HuaweiProject $huawei_project)
    {
        if (!$huawei_project->status) {
            abort(403, 'Acción no permitida');
        }

        $huawei_project->update([
            'status' => null
        ]);

        return redirect()->back();
    }

    public function resumeProject(HuaweiProject $huawei_project)
    {
        if ($huawei_project->status !== null) {
            abort(403, 'Acción no permitida');
        }

        $huawei_project->update([
            'status' => 1
        ]);

        return redirect()->back();
    }

    public function projectBalance(HuaweiProject $huawei_project)
    {
        return Inertia::render('Huawei/ProjectBalance', [
            'huawei_project' => $huawei_project
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'prefix' => 'required',
            'macro_project' => 'required',
            'cost_center_id' => 'required',

            'ot' => 'required',
            'huawei_site_id' => 'required',
            'assigned_diu' => 'required|unique:huawei_projects,assigned_diu',
            'zone' => 'required',
            'description' => 'nullable',
            'assignation_date' => 'required|date',

            'base_lines' => 'required|array|min:1',
            'schedule' => 'required|array|min:1',
        ]);
        
        DB::beginTransaction();

        try {
            $project = HuaweiProject::create([
                'name' => $request->name,
                'prefix' => $request->prefix,
                'cost_center_id' => $request->cost_center_id,
                'macro_project' => $request->macro_project,
    
                'ot' => $request->ot,
                'huawei_site_id' => $request->huawei_site_id,
                'assigned_diu' => $request->assigned_diu,
                'zone' => $request->zone,
                'description' => $request->description,
                'assignation_date' => $request->assignation_date,
                
                'status' => 1
            ]);
            
            foreach ($request->base_lines as $line) {
                HuaweiProjectEarning::create([
                    'code' => $line['code'],
                    'description' => $line['description'],
                    'unit' => $line['unit'],
                    'unit_price' => $line['unit_price'],
                    'quantity' => $line['quantity'],
                    'goal' => $line['goal'],
                    'observation' => $line['observation'],
                    'evidence' => $line['evidence'],
                    'huawei_project_id' => $project->id
                ]);
            }

            foreach ($request->schedule as $activity) {
                HuaweiProjectSchedule::create([
                    'activity' => $activity['activity'],
                    'days' => $activity['days'],
                    'huawei_project_id' => $project->id
                ]);
            }
            
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(403, 'Error al crear el proyecto');
        }

        DB::commit();

        return redirect()->back();
    }

    public function update(HuaweiProject $huawei_project, Request $request)
    {
        if (!$huawei_project->status) {
            abort(403, 'Acción no permitida');
        }

        $data = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);

        $huawei_project->update($data);

        return redirect()->back();
    }

    public function showPreReport(HuaweiProject $huawei_project)
    {
        if (!$huawei_project->pre_report) {
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

    public function deleteEmployee(HuaweiProjectEmployee $id)
    {
        $huawei_project = HuaweiProject::find($id->huawei_project->id);

        if (!$huawei_project->status) {
            abort(403, 'Acción no permitida');
        }

        $id->delete();

        return redirect()->back();
    }

    public function add_employee(HuaweiProject $huawei_project, Request $request)
    {
        if (!$huawei_project->status) {
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

    public function edit_employee(HuaweiProject $huawei_project, HuaweiProjectEmployee $id, Request $request)
    {
        if (!$huawei_project->status) {
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

    public function getSites()
    {
        return Inertia::render('Huawei/Sites', [
            'sites' => HuaweiSite::select('id', 'name', 'address')->orderBy('name')->paginate(10)
        ]);
    }

    public function searchSites($request)
    {
        $searchTerm = strtolower($request);

        $query = HuaweiSite::query();

        $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"])
            ->orWhereRaw('LOWER(address) LIKE?', ["%{$searchTerm}%"]);

        return Inertia::render('Huawei/Sites', [
            'sites' => $query->select('id', 'name', 'address')->orderBy('name')->get(),
            'search' => $request
        ]);
    }

    public function storeSite(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'address' => 'required'
        ]);

        HuaweiSite::create($data);

        return redirect()->back();
    }

    public function destroySite(HuaweiSite $site)
    {
        $site->delete();
        return redirect()->back();
    }

    public function updateSite(HuaweiSite $site, Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'address' => 'required'
        ]);

        $site->update($data);

        return redirect()->back();
    }

    public function verifySiteName(Request $request, $update = null)
    {
        $term = strtolower($request->input('name'));

        $sites = HuaweiSite::all()->pluck('name')->toArray();

        $closeMatchName = null;

        foreach ($sites as $site) {
            $similarity = 0;
            similar_text($term, strtolower($site), $similarity);

            if ($similarity > 65) {
                $closeMatchName = $site;
                break;
            }
        }

        if ($update && $closeMatchName && $closeMatchName === HuaweiSite::find($update)->name) {
            return response()->json([
                'message' => 'notfound',
                'status' => 'none'
            ]);
        }

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

    public function getCostSummary(HuaweiProject $huawei_project)
    {
        $prevExpense = HuaweiMonthlyExpense::with([
            'general_expense',
        ])
            ->where(function ($query) {
                $query->where('is_accepted', 1)
                    ->orWhere('is_accepted', null);
            })
            ->where('huawei_project_id', $huawei_project->id)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($prevExpense as $exp) {
            $exp->setAppends(['real_state', 'type']);
        }

        $acExpensesAmounts = $prevExpense->filter(function ($cost) {
            return $cost->type === 'Variable';
        })->groupBy('expense_type')->map(function ($group) {
            return [
                'expense_type' => $group->first()->expense_type,
                'total_amount' => $group->sum('amount'),
            ];
        })->values()->toArray();

        $scExpensesAmounts = $prevExpense->filter(function ($cost) {
            return $cost->type === 'Fijo';
        })->groupBy('expense_type')->map(function ($group) {
            return [
                'expense_type' => $group->first()->expense_type,
                'total_amount' => $group->sum('amount'),
            ];
        })->values()->toArray();

        return Inertia::render('Huawei/CostSummary', [
            'expenses' => $prevExpense,
            'acExpensesAmounts' => $acExpensesAmounts,
            'scExpensesAmounts' => $scExpensesAmounts,
            'huawei_project' => $huawei_project->makeHidden([
                'huawei_additional_costs',
                'huawei_project_earnings',
                'huawei_project_employees',
                'huawei_project_real_earnings',
                'huawei_project_resources',
                'huawei_static_costs'
            ])
        ]);
    }

    public function getExpensesByZone($huawei_project_id, $expenseType)
    {
        $prevExpense = HuaweiMonthlyExpense::with([
            'general_expense',
        ])
            ->where(function ($query) {
                $query->where('is_accepted', 1)
                    ->orWhere('is_accepted', null);
            })
            ->where('huawei_project_id', $huawei_project_id)
            ->where('expense_type', $expenseType)
            ->orderBy('created_at', 'desc')
            ->get();

        $groupedExpenses = $prevExpense->groupBy('zone')->map(function ($group) {
            return [
                'zone' => $group->first()->zone,
                'total_amount' => $group->sum('amount'),
            ];
        })->values()->toArray();

        return response()->json($groupedExpenses, 200);
    }

    //additional costs
    private static array $data;

    public function __construct()
    {
        self::$data = [
            'employees' => HuaweiConstants::getEmployees(),
            'static_expense_types' => HuaweiConstants::getStaticExpenseTypes(),
            'variable_expense_types' => HuaweiConstants::getVariableExpenseTypes(),
            'cdp_types' => HuaweiConstants::getCDPTypes(),
        ];
    }
    public function getAdditionalCosts(HuaweiProject $huawei_project, $mode = null)
    {
        $expenses = HuaweiMonthlyExpense::query()
            ->whereNotNull('huawei_project_id')
            ->where('huawei_project_id', $huawei_project->id)
            ->orderBy('expense_date', 'desc')
            ->get()
            ->filter(fn($expense) => $mode ? $expense->type === 'Fijo' : $expense->type === 'Variable');

    
        return Inertia::render('Huawei/AdditionalCosts', [
            'expense' => $expenses->values(),
            'project' => $huawei_project,
            'data' => self::$data,
            'mode' => $mode
        ]);
    }
    
    public function showImage(HuaweiMonthlyExpense $expense)
    {
        $imageToShow = $expense->image;
        $path = public_path("documents/huawei/monthly_expenses/$imageToShow");
        if (file_exists($path)) {
            ob_end_clean();
            return response()->file($path);
        }
        abort(403, 'Imagen no encontrada');
    }

    public function search_costs($huawei_project_id, Request $request, $mode = null)
    {
        $expensesQuery = HuaweiMonthlyExpense::where('huawei_project_id', $huawei_project_id)->orderBy('expense_date', 'desc');
        $summary = [
            'expense_types' => count($mode ? self::$data['static_expense_types'] : self::$data['variable_expense_types']),
            'employees' => count(self::$data['employees']),
            'cdp_types' => count(self::$data['cdp_types']),
        ];

        // Aplicar filtros de base de datos
        if (count($request->selectedExpenseTypes) < $summary['expense_types']) {
            $expensesQuery->whereIn('expense_type', $request->selectedExpenseTypes);
        }
        if (count($request->selectedCDPTypes) < $summary['cdp_types']) {
            $expensesQuery->whereIn('cdp_type', $request->selectedCDPTypes);
        }
        if (count($request->selectedEmployees) < $summary['employees']) {
            $expensesQuery->whereIn('employee', $request->selectedEmployees);
        }

        if ($request->exStartDate) {
            $expensesQuery->where('expense_date', '>=', $request->exStartDate);
        }
        if ($request->exEndDate) {
            $expensesQuery->where('expense_date', '<=', $request->exEndDate);
        }
        if ($request->exNoDate) {
            $expensesQuery->whereNull('expense_date');
        }
        if ($request->opStartDate) {
            $expensesQuery->where('ec_expense_date', '>=', $request->opStartDate);
        }
        if ($request->opEndDate) {
            $expensesQuery->where('ec_expense_date', '<=', $request->opEndDate);
        }
        if ($request->opNoDate) {
            $expensesQuery->whereNull('ec_expense_date');
        }
        $expenses = $expensesQuery->orderBy('expense_date', 'desc')
            ->get()
            ->filter(fn($expense) => $mode ? $expense->type === 'Fijo' : $expense->type === 'Variable');

        if (count($request->selectedStates) < 4) {
            $selectedStates = $request->selectedStates;

            $filteredStates = array_filter($selectedStates, function ($state) {
                return in_array($state, ["Aceptado", "Rechazado", "Pendiente", "Aceptado-Validado"]);
            });

            $expenses = $expenses->filter(function ($expense) use ($filteredStates) {
                return in_array($expense->real_state, $filteredStates);
            });
        }

        return response()->json(["expenses" => $expenses->values()->toArray()], 200);
    }


    public function searchAdditionalCosts(HuaweiProject $huawei_project, $request, $mode = null)
    {
        $searchTerm = strtolower($request);
        $expensesQuery = HuaweiMonthlyExpense::query()
            ->where('huawei_project_id', $huawei_project->id)
            ->where(function ($query) use ($searchTerm) {
                $query->whereRaw('LOWER(expense_type) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(employee) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(cdp_type) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(doc_number) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(op_number) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(ruc) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(description) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(ec_op_number) LIKE ?', ["%{$searchTerm}%"]);
            });

        $expenses = $expensesQuery->orderBy('expense_date', 'desc')
                        ->get()
                        ->filter(fn($expense) => $mode ? $expense->type === 'Fijo' : $expense->type === 'Variable');
        
        return Inertia::render('Huawei/AdditionalCosts', [
            'expense' => $expenses->values(),
            'project' => $huawei_project,
            'data' => self::$data,
            'search' => $request,
            'mode' => $mode
        ]);
    }

    public function exportAdditionalCosts(HuaweiProject $huawei_project_id, $mode = null)
    {
        $fileName = 'Gastos ' . ($mode ? 'Fijos' : 'Variables') . ' del Proyecto - ' . $huawei_project_id->name . '.xlsx';
    
        return Excel::download(new HuaweiMonthlyExport($mode, $huawei_project_id->id), $fileName);
    }
    
    private function sanitizeText($text, $mode)
    {
        if ($mode) {
            return ucwords(strtolower($text));
        } else {
            $text = strtoupper($text);

            $text = str_replace(
                ['Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ'],
                ['A', 'E', 'I', 'O', 'U', 'N'],
                $text
            );

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

            foreach ($resources as $resource) {
                $resource->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name = $this->sanitizeText2($resource->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name);
            }

            $equipments = HuaweiEquipment::where('prefix', $found_project->prefix)->with([
                'huawei_equipment_series.huawei_entry_detail' => function ($query) use ($found_project) {
                    $query->where('assigned_diu', $found_project->assigned_diu);
                }
            ])->get();

            $filteredEquipments = $equipments->filter(function ($equipment) {
                // Verifica la relación `huawei_equipment_series`
                if ($equipment->huawei_equipment_series) {
                    foreach ($equipment->huawei_equipment_series as $serie) {
                        // Verifica la relación `huawei_entry_detail`
                        if ($serie->huawei_entry_detail) {
                            if ($serie->huawei_entry_detail->state === 'Disponible') {
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

            foreach ($resources as $resource) {
                $resource->huawei_entry_detail->huawei_material->name = $this->sanitizeText2($resource->huawei_entry_detail->huawei_material->name);
            }

            $materials = HuaweiMaterial::where('prefix', $found_project->prefix)
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

    public function searchEntryDetails(HuaweiProject $huawei_project, $id, $equipment = null)
    {
        if ($equipment) {
            $entry_details = HuaweiEntryDetail::whereNull('huawei_material_id')->where('assigned_diu', $huawei_project->assigned_diu)
                ->whereHas('huawei_equipment_serie', function ($query) use ($id) {
                    $query->where('huawei_equipment_id', $id);
                })->with([
                        'huawei_equipment_serie' => function ($query) {
                            $query->select('id', 'serie_number');
                        }
                    ])->get()
                ->filter(function ($detail) {
                    return $detail->state == 'Disponible';
                })->map(function ($detail) {
                    return [
                        'id' => $detail->id,
                        'serie_number' => $detail->huawei_equipment_serie->serie_number,
                        'order_number' => $detail->order_number
                    ];
                });
        } else {
            $entry_details = HuaweiEntryDetail::select(
                'id',
                'huawei_material_id',
                'order_number',
                'quantity'
            )
                ->where('huawei_material_id', $id)
                ->get()
                ->makeHidden([
                    'refund_quantity',
                    'project_quantity',
                    'assigned_site',
                    'antiquation_state',
                    'instalation_state',
                    'huawei_project_resources'
                ])
                ->filter(function ($detail) {
                    return $detail->state == 'Disponible';
                });
        }
        return response()->json(['details' => $entry_details]);
    }

    public function searchResources($huawei_project, $request, $equipment = null)
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
                            $query->where('name', 'like', '%' . $searchTerm . '%');
                        })
                        ->orWhereHas('huawei_equipment_serie', function ($query) use ($searchTerm) {
                            $query->where('serie_number', 'like', '%' . $searchTerm . '%');
                        });
                });
            $resources = $query->get();
            foreach ($resources as $resource) {
                $resource->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name = $this->sanitizeText2($resource->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name);
            }
            $equipments = HuaweiEquipment::with([
                'huawei_equipment_series.huawei_entry_detail' => function ($query) use ($found_project) {
                    $query->where('assigned_diu', $found_project->assigned_diu);
                }
            ])->get();

            $filteredEquipments = $equipments->filter(function ($equipment) {
                // Verifica la relación `huawei_equipment_series`
                if ($equipment->huawei_equipment_series) {
                    foreach ($equipment->huawei_equipment_series as $serie) {
                        // Verifica la relación `huawei_entry_detail`
                        if ($serie->huawei_entry_detail) {
                            if ($serie->huawei_entry_detail->state === 'Disponible') {
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
                            $query->where('name', 'like', '%' . $searchTerm . '%');
                        });
                });
            $resources = $query->get();
            foreach ($resources as $resource) {
                $resource->huawei_entry_detail->huawei_material->name = $this->sanitizeText2($resource->huawei_entry_detail->huawei_material->name);
            }
            $materials = HuaweiMaterial::where('prefix', $found_project->prefix)
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

    public function storeProjectResource($huawei_project, Request $request, $equipment = null)
    {
        $found_project = HuaweiProject::find($huawei_project);

        if (!$found_project->status) {
            abort(403, 'Acción no permitida');
        }

        if ($equipment) {

            $request->validate([
                'huawei_entry_detail_ids' => 'required',
            ]);

            DB::beginTransaction();

            foreach ($request->huawei_entry_detail_ids as $detail) {
                $foundDetail = HuaweiEntryDetail::find($detail);
                if ($foundDetail->assigned_diu !== $found_project->assigned_diu) {
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

        } else {
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

    public function refundResource(HuaweiProjectResource $huawei_resource, Request $request, $equipment = null)
    {
        $found_project = HuaweiProject::find($huawei_resource->huawei_project_id);

        if (!$found_project->status) {
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

        if ($equipment) {
            $huawei_resource->update([
                'quantity' => 0
            ]);
        } else {
            $huawei_resource->update([
                'quantity' => $huawei_resource->quantity - $request->quantity
            ]);
        }

        return redirect()->back();
    }

    //liquidations
    public function geResourcesToLiquidate($huawei_project)
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

        $materials = HuaweiProjectResource::where('huawei_project_id', $huawei_project)
            ->where('quantity', '!=', 0)
            ->whereDoesntHave('huawei_project_liquidation')
            ->whereHas('huawei_entry_detail', function ($query) {
                $query->whereNull('huawei_equipment_serie_id');
            })
            ->with('huawei_entry_detail.huawei_material')
            ->get();

        $data = [
            'series' => $equipments->map(function ($resource) {
                return $resource->huawei_entry_detail->huawei_equipment_serie->serie_number ?? null;
            })->filter()->unique()->values(),
            'material_order_numbers' => $materials->map(function ($resource) {
                return $resource->huawei_entry_detail->order_number ?? null;
            })->filter()->unique()->values(),
            'equipment_order_numbers' => $equipments->map(function ($resource) {
                return $resource->huawei_entry_detail->order_number ?? null;
            })->filter()->unique()->values(),
        ];

        foreach ($equipments as $resource) {
            $resource->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name = $this->sanitizeText2($resource->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name);
        }

        foreach ($materials as $resource) {
            $resource->huawei_entry_detail->huawei_material->name = $this->sanitizeText2($resource->huawei_entry_detail->huawei_material->name);
        }

        return Inertia::render('Huawei/Liquidations', [
            'equipments' => $equipments,
            'materials' => $materials,
            'huawei_project' => $huawei_project,
            'project_name' => $project_name,
            'projectState' => $project->status,
            'data' => $data
        ]);
    }

    public function search_advance_liquidate($huawei_project, Request $request)
    {
        $equipments = HuaweiProjectResource::where('huawei_project_id', $huawei_project)
            ->where('quantity', '!=', 0)
            ->whereDoesntHave('huawei_project_liquidation')
            ->whereHas('huawei_entry_detail', function ($query) {
                $query->whereNull('huawei_material_id');
            })
            ->with('huawei_entry_detail.huawei_equipment_serie.huawei_equipment');

        $materials = HuaweiProjectResource::where('huawei_project_id', $huawei_project)
            ->where('quantity', '!=', 0)
            ->whereDoesntHave('huawei_project_liquidation')
            ->whereHas('huawei_entry_detail', function ($query) {
                $query->whereNull('huawei_equipment_serie_id');
            })
            ->with('huawei_entry_detail.huawei_material');

        $equipmentsForCount = $equipments->get();
        $materialsForCount = $materials->get();

        $data = [
            'series' => $equipmentsForCount->map(function ($resource) {
                return $resource->huawei_entry_detail->huawei_equipment_serie->serie_number ?? null;
            })->filter()->unique()->values()->count(),
            'material_order_numbers' => $materialsForCount->map(function ($resource) {
                return $resource->huawei_entry_detail->order_number ?? null;
            })->filter()->unique()->values()->count(),
            'equipment_order_numbers' => $equipmentsForCount->map(function ($resource) {
                return $resource->huawei_entry_detail->order_number ?? null;
            })->filter()->unique()->values()->count(),
        ];

        if (count($request->selectedSeries) < $data['series']) {
            $series = $request->selectedSeries;
            $equipments->whereHas('huawei_entry_detail.huawei_equipment_serie', function ($subQuery) use ($series) {
                $subQuery->whereIn('serie_number', $series);
            });
        }
        if (count($request->selectedEquipmentOrderNumbers) < $data['equipment_order_numbers']) {
            $equipment_order_numbers = $request->selectedEquipmentOrderNumbers;
            $equipments->whereHas('huawei_entry_detail', function ($subQuery) use ($equipment_order_numbers) {
                $subQuery->whereIn('order_number', $equipment_order_numbers);
            });
        }
        if (count($request->selectedMaterialOrderNumbers) < $data['material_order_numbers']) {
            $material_order_numbers = $request->selectedMaterialOrderNumbers;
            $materials->whereHas('huawei_entry_detail', function ($subQuery) use ($material_order_numbers) {
                $subQuery->whereIn('order_number', $material_order_numbers);
            });
        }

        $equipments = $equipments->get();
        $materials = $materials->get();

        foreach ($equipments as $equipment) {
            $equipment->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name = $this->sanitizeText2($equipment->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name);
        }

        foreach ($materials as $material) {
            $material->huawei_entry_detail->huawei_material->name = $this->sanitizeText2($material->huawei_entry_detail->huawei_material->name);
        }

        return response()->json(['materials' => $materials, 'equipments' => $equipments], 200);
    }

    public function liquidate(HuaweiProject $huawei_project, Request $request, $equipment = null)
    {

        if (!$huawei_project->status) {
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

        if ($equipment) {
            HuaweiProjectLiquidation::create([
                'huawei_project_resource_id' => $request->huawei_project_resource_id,
                'instalation_date' => $request->instalation_date,
                'liquidated_quantity' => 1
            ]);
        } else {
            HuaweiProjectLiquidation::create([
                'huawei_project_resource_id' => $request->huawei_project_resource_id,
                'instalation_date' => $request->instalation_date,
                'liquidated_quantity' => $request->liquidated_quantity
            ]);
        }
    }

    public function massiveLiquidation(Request $request, $equipment = null)
    {
        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
            'instalation_date' => 'required|date',
        ]);

        foreach ($data['ids'] as $item) {
            $huawei_project_resource = HuaweiProjectResource::with('huawei_project_liquidation')->find($item);
            if (!$huawei_project_resource || $huawei_project_resource->quantity <= 0 || $huawei_project_resource->huawei_project_liquidation !== null) {
                abort(403, 'No se puede liquidar este recurso debido a restricciones.');
            }
            if ($equipment) {
                HuaweiProjectLiquidation::create([
                    'huawei_project_resource_id' => $item,
                    'instalation_date' => $request->instalation_date,
                    'liquidated_quantity' => 1
                ]);
            } else {
                HuaweiProjectLiquidation::create([
                    'huawei_project_resource_id' => $item,
                    'instalation_date' => $request->instalation_date,
                    'liquidated_quantity' => $huawei_project_resource->quantity
                ]);
            }
        }
        return redirect()->back();
    }

    public function liquidationsHistory($huawei_project, $equipment = null)
    {
        $project = HuaweiProject::find($huawei_project);
        $project_name = $project->name . ' / ' . $project->code;
        if ($equipment) {
            $liquidations = HuaweiProjectLiquidation::whereHas('huawei_project_resource.huawei_entry_detail', function ($query) {
                $query->whereNull('huawei_material_id');
            })
                ->whereHas('huawei_project_resource', function ($query) use ($huawei_project) {
                    $query->where('huawei_project_id', $huawei_project);
                })
                ->with('huawei_project_resource.huawei_entry_detail.huawei_equipment_serie.huawei_equipment')
                ->paginate(10);

            foreach ($liquidations as $resource) {
                $resource->huawei_project_resource->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name = $this->sanitizeText2($resource->huawei_project_resource->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name);
            }

        } else {
            $liquidations = HuaweiProjectLiquidation::whereHas('huawei_project_resource.huawei_entry_detail', function ($query) {
                $query->whereNull('huawei_equipment_serie_id');
            })
                ->whereHas('huawei_project_resource', function ($query) use ($huawei_project) {
                    $query->where('huawei_project_id', $huawei_project);
                })
                ->with('huawei_project_resource.huawei_entry_detail.huawei_material')
                ->paginate(10);
            foreach ($liquidations as $resource) {
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

    public function getEarnings(HuaweiProject $huawei_project)
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

    public function searchEarnings(HuaweiProject $huawei_project, $request)
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

    public function storeEarning(Request $request)
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

    public function updateEarningState(HuaweiProject $huawei_project, HuaweiProjectEarning $earning, Request $request)
    {
        if (!$huawei_project->status) {
            abort(403, 'Acción no permitida');
        }
        $data = $request->validate([
            'state' => 'required'
        ]);

        $earning->update($data);

        return redirect()->back();
    }

    public function updateEarning(HuaweiProjectEarning $huawei_project_earning, Request $request)
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

    public function deleteEarning(HuaweiProjectEarning $huawei_project_earning)
    {
        $found_project = HuaweiProject::find($huawei_project_earning->huawei_project_id);

        if (!$found_project->status) {
            return abort(403, 'Acción no permitida');
        }

        $huawei_project_earning->delete();
        return redirect()->back();
    }

    public function importEarnings($huawei_project, Request $request)
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

            $rowObject = (object) [
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
            } else {
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

    public function exportEarnings(HuaweiProject $huawei_project)
    {
        return Excel::download(new HuaweiProjectEarningsExport($huawei_project->id), 'Ingresos proyectados de ' . $huawei_project->assigned_diu . '.xlsx');
    }

    //real_earnings

    public function getRealEarnings(HuaweiProject $huawei_project)
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

    public function searchRealEarnings(HuaweiProject $huawei_project, $request)
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

    public function storeRealEarning(Request $request)
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

    public function updateRealEarning(HuaweiProjectRealEarning $huawei_project_real_earning, Request $request)
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

    public function deleteRealEarning(HuaweiProjectRealEarning $huawei_project_real_earning)
    {
        $found_project = HuaweiProject::find($huawei_project_real_earning->huawei_project_id);

        if (!$found_project->status) {
            return abort(403, 'Acción no permitida');
        }

        $huawei_project_real_earning->delete();
        return redirect()->back();
    }

    public function exportRealEarnings(HuaweiProject $huawei_project)
    {
        return Excel::download(new HuaweiProjectRealEarningsExport($huawei_project->id), 'Ingresos reales de ' . $huawei_project->assigned_diu . '.xlsx');
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

    public function verifyImportRealEarnings($huawei_project, Request $request)
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

            $rowObject = (object) [
                'invoice_number' => $row['A'],
            ];

            $rowsAsObjects[] = $rowObject;
        }

        foreach ($rowsAsObjects as $item) {
            $found_earning = HuaweiProjectRealEarning::where('invoice_number', $item->invoice_number)->where('huawei_project_id', $huawei_project)->first();
            if ($found_earning) {
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

    private function sanitizeText2($text)
    {
        // Usa una expresión regular para eliminar el texto entre paréntesis al principio del texto
        return preg_replace('/^\(.*?\)\s*/', '', $text);
    }
}
