<?php

namespace App\Http\Controllers\Huawei;

use App\Constants\HuaweiConstants;
use App\Exports\HuaweiMonthlyExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Huawei\HuaweiMonthlyExpenseRequest;
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
            'assigned_dius' => $expenses
                ->map(fn($expense) => $expense->huawei_project?->assigned_diu)
                ->unique()
                ->filter()
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
        $filteredExpenses = $expensesQuery->get()
            ->filter(fn($expense) => $mode ? $expense->type === 'Fijo' : $expense->type === 'Variable');
        $summary = [
            'expense_types' => count($mode ? self::$data['static_expense_types'] : self::$data['variable_expense_types']),
            'zones' => $expensesQuery->get()->pluck('zone')->unique()->values()->count(),
            'assigned_dius' => $filteredExpenses
                ->map(fn($expense) => $expense->huawei_project?->assigned_diu)
                ->unique()
                ->values()->count(),
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
        $endCell = 'N' . $sheet->getHighestRow();
        $range = "$startCell:$endCell";

        // Leer el rango especificado
        $data = $sheet->rangeToArray($range, null, true, true, true);

        // Array para almacenar los objetos
        $rowsAsObjects = [];

        // Recorrer las filas y convertir a objetos

        foreach ($data as $index => $row) {
            if ($index === 1)
                continue;
            $isEmptyRow = empty($row['A']) && empty($row['B']) && empty($row['C']) && empty($row['D']) &&
                empty($row['E']) && empty($row['F']) && empty($row['G']) && empty($row['H']) &&
                empty($row['I']) && empty($row['J']) && empty($row['L']) && empty($row['M']) &&
                empty($row['N']);

            if ($isEmptyRow) {
                break;
            }
            $rowObject = (object) [
                'employee' => $this->sanitizeText($row['A'], false),
                'project_id' => $row['B'] ? HuaweiProject::where('assigned_diu', $row['B'])->first()->id : null,
                'expense_date' => $this->sanitizeDate($row['C']),
                'cdp_type' => $this->sanitizeText($row['D'], true),
                'doc_number' => $row['E'],
                'op_number' => $row['F'],
                'ruc' => $row['G'],
                'amount' => $this->sanitizeNumber($row['H']),
                'description' => $row['I'],
                'expense_type' => $row['J'],
                'ec_expense_date' => $this->sanitizeDate($row['L']),
                'ec_op_number' => $row['M'],
                'ec_amount' => $this->sanitizeNumber($row['N']),
            ];

            $rowsAsObjects[] = $rowObject;
        }

        DB::beginTransaction();

        foreach ($rowsAsObjects as $item) {

            if (!$item->expense_type || !$item->expense_date || !$item->cdp_type || !$item->amount || !$item->description) {
                DB::rollBack();
                return back()->withErrors(['message' => 'Llene todos los campos']);
            }

            HuaweiMonthlyExpense::create([
                'expense_type' => $item->expense_type,
                'zone' => $item->project_id ? HuaweiProject::find($item->project_id)->huawei_site->name : 'Sin zona',
                'employee' => $item->employee,
                'expense_date' => $item->expense_date,
                'cdp_type' => $item->cdp_type,
                'doc_number' => $item->doc_number,
                'op_number' => $item->op_number,
                'ruc' => $item->ruc,
                'description' => $item->description,
                'amount' => $item->amount,
                'ec_expense_date' => $item->ec_expense_date,
                'ec_op_number' => $item->ec_op_number,
                'ec_amount' => $item->ec_amount,
                'huawei_project_id' => $item->project_id,
            ]);
        }
        DB::commit();
        return redirect()->back();
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
        // Verifica si el dato es nulo o vacío
        if (empty($date) || !is_string($date)) {
            return null;
        }

        // Elimina espacios innecesarios
        $date = trim($date);

        // Formatos permitidos
        $formats = [
            'd/n/Y', // Soporta días y meses sin ceros a la izquierda
            'd/m/Y',
            'Y-m-d',
            'd-m-Y',
        ];

        // Intentar convertir usando cada formato
        foreach ($formats as $format) {
            try {
                $parsedDate = Carbon::createFromFormat($format, $date);

                // Verifica si la fecha interpretada coincide con la original
                if ($parsedDate !== false) {
                    return $parsedDate->format('Y-m-d');
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        return null; // Si no coincide con ningún formato, retorna null
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

    private function sanitizeNumber($text)
    {
        // Remover todos los caracteres que no sean dígitos o puntos
        $sanitized = preg_replace('/[^0-9.]/', '', $text);

        // Si hay más de un punto, conservar solo el primero
        if (substr_count($sanitized, '.') > 1) {
            $parts = explode('.', $sanitized);
            $sanitized = array_shift($parts) . '.' . implode('', $parts);
        }

        // Eliminar ceros a la izquierda innecesarios
        $sanitized = ltrim($sanitized, '0');

        // Si después de limpiar queda vacío, devolver "0"
        return $sanitized === '' ? '0' : $sanitized;
    }


}
