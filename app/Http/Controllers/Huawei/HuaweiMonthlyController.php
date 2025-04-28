<?php

namespace App\Http\Controllers\Huawei;

use App\Constants\HuaweiConstants;
use App\Exports\HuaweiMonthlyExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Huawei\HuaweiMonthlyExpenseRequest;
use App\Imports\HuaweiExpensesImport;
use App\Models\Employee;
use App\Models\HuaweiMonthlyExpense;
use App\Models\HuaweiMonthlyProject;
use App\Models\HuaweiProject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Log;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class HuaweiMonthlyController extends Controller
{

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
    //expenses

    public function getGeneralBalance()
    {
        $prevExpense = HuaweiMonthlyExpense::with([
            'general_expense',
        ])
            ->where(function ($query) {
                $query->where('is_accepted', 1)
                    ->orWhere('is_accepted', null);
            })
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

        return Inertia::render('Huawei/GeneralBalance', [
            'expenses' => $prevExpense,
            'acExpensesAmounts' => $acExpensesAmounts,
            'scExpensesAmounts' => $scExpensesAmounts,
        ]);
    }

    public function getExpensesByZone($expenseType)
    {
        $prevExpense = HuaweiMonthlyExpense::with([
            'general_expense',
        ])
            ->where(function ($query) {
                $query->where('is_accepted', 1)
                    ->orWhere('is_accepted', null);
            })
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


    public function getExpenses($mode = null)
    {
        $expenses = HuaweiMonthlyExpense::query()
            ->orderBy('expense_date', 'desc')
            ->with([
                'huawei_project' => function ($query) {
                    $query->select('id', 'assigned_diu', 'huawei_site_id', 'macro_project');
                },
                'huawei_project.huawei_site'
            ])
            ->get()
            ->each(fn($expense) => $expense->huawei_project?->makeHidden([
                'huawei_additional_costs',
                'huawei_static_costs',
                'huawei_project_employees',
                'huawei_project_resources',
                'huawei_project_earnings',
                'huawei_project_real_earnings',
                'huawei_project_stages',
                'huawei_monthly_expenses',
                'code',
                'total_earnings',
                'total_real_earnings',
                'total_real_earnings_without_deposit',
                'total_project_cost',
                'total_employee_costs',
                'total_essalud_employee_cost',
                'additional_cost_total',
                'static_cost_total',
                'materials_in_project',
                'equipments_in_project',
                'materials_liquidated',
                'equipments_liquidated',
            ]))
            ->filter(fn($expense) => $mode ? $expense->type === 'Fijo' : $expense->type === 'Variable');

        $summary = [
            'zones' => $expenses->pluck('zone')->unique()->values(),
            'op_numbers' => $expenses->pluck('ec_op_number')->map(fn($item) => (string) $item)->uniqueStrict()->values(),
            'assigned_dius' => $expenses
                ->map(fn($expense) => $expense->huawei_project?->assigned_diu)
                ->filter()
                ->uniqueStrict()
                ->values(),
        ];

        return Inertia::render('Huawei/MonthlyExpenses', [
            'expense' => $expenses->values(),
            'summary' => $summary,
            'mode' => $mode,
            'data' => self::$data,
        ]);
    }


    public function searchExpenses($request, $mode = null)
    {
        $searchTerm = strtolower($request);

        $expensesQuery = HuaweiMonthlyExpense::query()
            ->where(function ($query) use ($searchTerm) {
                $query->whereRaw('LOWER(expense_type) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(zone) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(employee) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(cdp_type) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(doc_number) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(op_number) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(ruc) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(description) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(ec_op_number) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereHas('huawei_project', function ($query) use ($searchTerm) {
                        $query->whereRaw('LOWER(assigned_diu) LIKE ?', ["%{$searchTerm}%"]);
                    });
            });

        // Ejecutar la consulta y obtener los resultados
        $expenses = $expensesQuery->orderBy('expense_date', 'desc')
            ->with([
                'huawei_project' => function ($query) {
                    $query->select('id', 'assigned_diu', 'huawei_site_id', 'macro_project');
                },
                'huawei_project.huawei_site'
            ])
            ->get()
            ->each(fn($expense) => $expense->huawei_project?->makeHidden([
                'huawei_additional_costs',
                'huawei_static_costs',
                'huawei_project_employees',
                'huawei_project_resources',
                'huawei_project_earnings',
                'huawei_project_real_earnings',
                'huawei_project_stages',
                'huawei_monthly_expenses',
                'code',
                'total_earnings',
                'total_real_earnings',
                'total_real_earnings_without_deposit',
                'total_project_cost',
                'total_employee_costs',
                'total_essalud_employee_cost',
                'additional_cost_total',
                'static_cost_total',
                'materials_in_project',
                'equipments_in_project',
                'materials_liquidated',
                'equipments_liquidated',
            ]))
            ->filter(fn($expense) => $mode ? $expense->type === 'Fijo' : $expense->type === 'Variable');

        $filteredExpenses = $expenses->filter(fn($expense) => $mode ? $expense->type === 'Fijo' : $expense->type === 'Variable');

        $summary = [
            'zones' => $expenses->pluck('zone')->unique()->values(),
            'op_numbers' => $expenses->pluck('ec_op_number')->map(fn($item) => (string) $item)->uniqueStrict()->values(),
            'assigned_dius' => $filteredExpenses
                ->map(fn($expense): mixed => $expense->huawei_project?->assigned_diu)
                ->unique()
                ->values(),
        ];

        return Inertia::render('Huawei/MonthlyExpenses', [
            'expense' => $expenses->values(),
            'search' => $request,
            'data' => self::$data,
            'summary' => $summary,
            'mode' => $mode,
        ]);
    }

    public function searchAdvance(Request $request, $mode = null)
    {
        // Iniciar la consulta base de expenses
        $expensesQuery = HuaweiMonthlyExpense::orderBy('expense_date', 'desc');
        $allExpenses = $expensesQuery->get();

        $filteredExpenses = $allExpenses->filter(fn($expense) => $mode ? $expense->type === 'Fijo' : $expense->type === 'Variable');

        $summary = [
            'expense_types' => count($mode ? self::$data['static_expense_types'] : self::$data['variable_expense_types']),
            'zones' => $allExpenses->pluck('zone')->unique()->count(),
            'op_numbers' => $allExpenses->pluck('ec_op_number')->map(fn($item) => (string) $item)->uniqueStrict()->count(), // <-- Aquí el cambio
            'assigned_dius' => $filteredExpenses
                ->map(fn($expense) => $expense->huawei_project?->assigned_diu)
                ->filter()
                ->uniqueStrict()
                ->count(),
            'employees' => count(self::$data['employees']),
            'cdp_types' => count(self::$data['cdp_types']),
        ];


        if (!empty($request->search)) {
            $searchTerm = strtolower($request->search);
            $expensesQuery->where(function ($query) use ($searchTerm) {
                $query->whereRaw('LOWER(expense_type) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(zone) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(employee) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(cdp_type) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(doc_number) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(op_number) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(ruc) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(description) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(ec_op_number) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereHas('huawei_project', function ($query) use ($searchTerm) {
                        $query->whereRaw('LOWER(assigned_diu) LIKE ?', ["%{$searchTerm}%"]);
                    });
            });
        }

        if (count($request->selectedExpenseTypes) < $summary['expense_types']) {
            $expensesQuery->whereIn('expense_type', $request->selectedExpenseTypes);
        }
        if (count($request->selectedCDPTypes) < $summary['cdp_types']) {
            $expensesQuery->whereIn('cdp_type', $request->selectedCDPTypes);
        }
        if (count($request->selectedEmployees) < $summary['employees']) {
            $expensesQuery->whereIn('employee', $request->selectedEmployees);
        }
        if (count($request->selectedZones) < $summary['zones']) {
            $expensesQuery->whereIn('zone', $request->selectedZones);
        }
        if (count($request->selectedDus) < $summary['assigned_dius'] + 1) {
            $selectedDus = $request->selectedDus;

            $expensesQuery->where(function ($query) use ($selectedDus) {
                if (in_array('(vacio)', $selectedDus)) {
                    $query->whereHas('huawei_project', function ($subQuery) use ($selectedDus) {
                        $subQuery->whereIn('assigned_diu', array_filter($selectedDus, fn($du) => $du !== '(vacio)'));
                    })->orWhereNull('huawei_project_id');
                } else {
                    $query->whereHas('huawei_project', function ($subQuery) use ($selectedDus) {
                        $subQuery->whereIn('assigned_diu', $selectedDus);
                    });
                }
            });
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
        if (count($request->ecOpNumbers) < $summary['op_numbers']) {
            $expensesQuery->whereIn('ec_op_number', $request->ecOpNumbers);
        }
        $expenses = $expensesQuery->orderBy('expense_date', 'desc')
            ->with([
                'huawei_project' => function ($query) {
                    $query->select('id', 'assigned_diu', 'huawei_site_id', 'macro_project');
                },
                'huawei_project.huawei_site'
            ])
            ->get()
            ->each(fn($expense) => $expense->huawei_project?->makeHidden([
                'huawei_additional_costs',
                'huawei_static_costs',
                'huawei_project_employees',
                'huawei_project_resources',
                'huawei_project_earnings',
                'huawei_project_real_earnings',
                'huawei_project_stages',
                'huawei_monthly_expenses',
                'code',
                'total_earnings',
                'total_real_earnings',
                'total_real_earnings_without_deposit',
                'total_project_cost',
                'total_employee_costs',
                'total_essalud_employee_cost',
                'additional_cost_total',
                'static_cost_total',
                'materials_in_project',
                'equipments_in_project',
                'materials_liquidated',
                'equipments_liquidated',
            ]))
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

        // Convertir la colección a array antes de enviarla en la respuesta JSON
        return response()->json(["expenses" => $expenses->values()->toArray()], 200);
    }


    public function storeExpense(HuaweiMonthlyExpenseRequest $request)
    {
        try{
            $data = $request->validated();

            if ($data['huawei_project_id']) {
                $data['zone'] = HuaweiProject::find($data['huawei_project_id'])->huawei_site->name;
            } else {
                $data['zone'] = "Sin zona";
            }
            $expense = HuaweiMonthlyExpense::create($data);

            $expenseDirectory = 'documents/huawei/monthly_expenses/';

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = "expense_{$expense->id}_" . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path($expenseDirectory), $filename);
                $expense->update(['image' => $filename]);
            }

            if ($expense->huawei_project_id) {
                $expense->load('huawei_project.huawei_site');
            }
            return response()->json($expense, 200);
        }
        
        catch (\Exception $e) {
            return response()->json(['error'=>$e]);
        }
    }

    public function updateExpense(HuaweiMonthlyExpense $expense, HuaweiMonthlyExpenseRequest $request)
    {
        $data = $request->validated();

        if ($data['huawei_project_id']) {
            $data['zone'] = HuaweiProject::find($data['huawei_project_id'])->huawei_site->name;
        } else {
            $data['zone'] = "Sin zona";
        }

        $expenseDirectory = 'documents/huawei/monthly_expenses/';

        if ($request->hasFile('image')) {
            if ($expense->image) {
                $oldPath = public_path($expenseDirectory . $expense->image);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $imageFile = $request->file('image');
            $filename = "expense_{$expense->id}_" . time() . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move(public_path($expenseDirectory), $filename);
            $data['image'] = $filename;
        }


        $expense->update($data);
        $expense->load('huawei_project.huawei_site');


        return response()->json($expense, 200);
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

    public function deleteExpense(HuaweiMonthlyExpense $expense)
    {
        $expenseDirectory = 'documents/huawei/monthly_expenses/';

        if ($expense->image) {
            $oldPath = public_path($expenseDirectory . $expense->image);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $expense->delete();
        return response()->json(true, 200);
    }

    public function validateExpense(Request $request, HuaweiMonthlyExpense $expense)
    {
        $validatedData = $request->validate([
            'is_accepted' => 'required|boolean'
        ]);

        $expense->update($validatedData);
        $expenseTo = HuaweiMonthlyExpense::where('id', $expense->id)->with('huawei_project.huawei_site')->first();
        return response()->json($expenseTo, 200);
    }

    public function exportMonthlyExpenses($mode = null)
    {
        return Excel::download(new HuaweiMonthlyExport($mode), 'Gastos Generales ' . ($mode ? 'Fijos' : 'Variables') . ' Huawei.xlsx');
    }

    public function importCosts(Request $request)
    {
        $data = $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new HuaweiExpensesImport, $data['file']);
        } catch (\Throwable $e) {
            back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function downloadTemplate()
    {
        $templatePath = public_path('documents/huawei/resources/Data Structure - Monthly Expenses.xlsx');
        ob_end_clean();
        return response()->download($templatePath, 'Estructura de Datos - Gastos Mensuales Huawei.xlsx');
    }

    public function massiveUpdate(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
            'ec_expense_date' => 'required|date',
            'ec_op_number' => 'required|min:6',
        ]);

        foreach ($data['ids'] as $id) {
            $monthlyExpense = HuaweiMonthlyExpense::find($id);

            if ($monthlyExpense) {
                $monthlyExpense->update([
                    'ec_expense_date' => $data['ec_expense_date'],
                    'ec_op_number' => $data['ec_op_number'],
                ]);
            }
        }

        $updatedCosts = HuaweiMonthlyExpense::whereIn('id', $data['ids'])->with('huawei_project')->get();

        return response()->json($updatedCosts, 200);
    }

    public function massiveValidate(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
            'state' => 'required'
        ]);

        DB::beginTransaction();

        foreach ($data['ids'] as $item) {
            $expense = HuaweiMonthlyExpense::find($item);
            if ($expense->is_accepted !== null) {
                DB::rollBack();
                abort(403, 'Acción no permitida');
            }
            $expense->update([
                'is_accepted' => $data['state']
            ]);
        }
        DB::commit();
        $updatedCosts = HuaweiMonthlyExpense::whereIn('id', $data['ids'])
            ->with('huawei_project.huawei_site')->get();

        return response()->json($updatedCosts, 200);
    }


    public function fetchSites($macro)
    {
        $projects = HuaweiProject::where('macro_project', $macro)->get();

        $sites = $projects->flatMap(function ($project) {
            return $project->huawei_site()->get()->map(function ($site) {
                return [
                    'id' => $site->id,
                    'name' => $site->name,
                ];
            });
        })->unique('id');

        return response()->json($sites, 200);
    }

    public function fetchProjects($macro, $site_id)
    {
        $projects = HuaweiProject::select('id', 'name', 'assigned_diu')
            ->where('macro_project', $macro)
            ->where('huawei_site_id', $site_id)
            ->get()
            ->makeHidden([
                'code',
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
            ])
            ->filter(function ($project) {
                return $project->state == 1;
            });

        return response()->json($projects, 200);
    }


    private function sanitizeDate($date)
    {
        if (empty($date) || !is_string($date)) {
            return "Error: La fecha es nula o no es una cadena válida.";
        }
    
        $date = trim($date);
    
        if (!preg_match('/^\d{1,2}-\d{1,2}-\d{4}$/', $date)) {
            return null;
        }

        try {
            $parsedDate = Carbon::createFromFormat('d-m-Y', $date);
            return $parsedDate->format('Y-m-d');
            
        } catch (\Exception $e) {
            return null;
        }
    }
    

    private function sanitizeText($text, $mode)
    {
        if ($mode) {
            $text = mb_convert_case(mb_strtolower($text, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
            return $text;
        } else {
            $text = strtoupper($text);

            $text = str_replace(
                ['Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ'],
                ['A', 'E', 'I', 'O', 'U', 'N'],
                $text
            );

            return $text;
        }
    }
}
