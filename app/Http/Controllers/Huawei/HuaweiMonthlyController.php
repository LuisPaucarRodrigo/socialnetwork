<?php

namespace App\Http\Controllers\Huawei;

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
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class HuaweiMonthlyController extends Controller
{
    public function getProjects()
    {
        return Inertia::render('Huawei/MonthlyProjects', [
            'projects' => HuaweiMonthlyProject::orderBy('created_at', 'desc')
                ->paginate(10),
        ]);
    }

    public function searchProjects($searchTerm)
    {
        $searchTerm = strtolower($searchTerm);
        $query = HuaweiMonthlyProject::query();

        $projects = $query->whereRaw('LOWER(description) LIKE ?', ["%{$searchTerm}%"])
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Huawei/MonthlyProjects', [
            'projects' => $projects,
            'search' => $searchTerm
        ]);
    }


    public function storeProject(Request $request)
    {
        $data = $request->validate([
            'date' => 'required',
            'description' => 'required',
        ]);

        $project = HuaweiMonthlyProject::create($data);

        return redirect()->back();
    }

    public function updateProject(HuaweiMonthlyProject $project, Request $request)
    {
        $data = $request->validate([
            'date' => 'required',
            'description' => 'required',
        ]);

        $project->update($data);

        return redirect()->back();
    }

    //expenses
    public function getExpenses(HuaweiMonthlyProject $project)
    {
        $expenses = HuaweiMonthlyExpense::where('huawei_monthly_project_id', $project->id)
            ->with('huawei_project.huawei_site')
            ->orderBy('expense_date', 'desc');
        $filteredExpenses = $expenses->get()->filter(fn($expense) => $expense->huawei_project);

        $summary = [
            'zones' => $expenses->get()->pluck('zone')->unique()->values(),
            'assigned_dius' => $filteredExpenses
                ->map(fn($expense) => $expense->huawei_project?->assigned_diu)
                ->unique()
                ->values(),
        ];
        return Inertia::render('Huawei/MonthlyExpenses', [
            'expense' => $expenses->paginate(15),
            'project' => $project,
            'summary' => $summary,
        ]);
    }

    public function searchExpenses(HuaweiMonthlyProject $project, $request)
    {
        $searchTerm = strtolower($request);

        $expensesQuery = HuaweiMonthlyExpense::query()
            ->where('huawei_monthly_project_id', $project->id)
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
            ->with('huawei_project.huawei_site')
            ->get();

        $filteredExpenses = $expenses->filter(fn($expense) => $expense->huawei_project);

        $summary = [
            'zones' => $expenses->pluck('zone')->unique()->values(),
            'assigned_dius' => $filteredExpenses
                ->map(fn($expense): mixed => $expense->huawei_project->assigned_diu)
                ->unique()
                ->values(),
        ];

        return Inertia::render('Huawei/MonthlyExpenses', [
            'expense' => $expenses,
            'project' => $project,
            'search' => $request,
            'summary' => $summary,
        ]);
    }

    public function searchAdvance(HuaweiMonthlyProject $project, Request $request)
    {
        // Iniciar la consulta base de expenses
        $expensesQuery = HuaweiMonthlyExpense::where('huawei_monthly_project_id', $project->id);
        $filteredExpenses = $expensesQuery->get()->filter(fn($expense) => $expense->huawei_project);
        $summary = [
            'zones' => $expensesQuery->get()->pluck('zone')->unique()->values()->count(),
            'assigned_dius' => $filteredExpenses
                ->map(fn($expense) => $expense->huawei_project->assigned_diu)
                ->unique()
                ->values()->count(),
        ];

        $employeeCount = 10;

        // Aplicar filtros de base de datos
        if (count($request->selectedExpenseTypes) < 9) {
            $expensesQuery->whereIn('expense_type', $request->selectedExpenseTypes);
        }
        if (count($request->selectedCDPTypes) < 7) {
            $expensesQuery->whereIn('cdp_type', $request->selectedCDPTypes);
        }
        if (count($request->selectedEmployees) < $employeeCount) {
            $expensesQuery->whereIn('employee', $request->selectedEmployees);
        }
        if (count($request->selectedZones) < $summary['zones']) {
            $expensesQuery->whereIn('zone', $request->selectedZones);
        }
        if (count($request->selectedDus) < $summary['assigned_dius'] + 1) {
            $selectedDus = $request->selectedDus;
            if (!in_array('(vacio)', $selectedDus)) {
                $expensesQuery->whereNotNull('huawei_project_id')->whereHas('huawei_project', function ($query) use ($selectedDus) {
                    $query->whereIn('assigned_diu', $selectedDus);
                });
            } else {
                $expensesQuery->whereHas('huawei_project', function ($query) use ($selectedDus) {
                    $query->whereIn('assigned_diu', $selectedDus);
                })->orWhereNull('huawei_project_id');
            }
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

        // Obtener todas las expenses según los filtros de base de datos
        $expenses = $expensesQuery->orderBy('expense_date')
            ->with('huawei_project.huawei_site')
            ->orderBy('expense_date', 'desc')
            ->get();

        // Si se han seleccionado menos de 4 estados, filtrar las expenses por el campo real_state
        if (count($request->selectedStates) < 4) {
            $selectedStates = $request->selectedStates;

            // Filtrar solo los estados válidos
            $filteredStates = array_filter($selectedStates, function ($state) {
                return in_array($state, ["Aceptado", "Rechazado", "Pendiente", "Aceptado-Validado"]);
            });

            // Filtrar la colección de expenses por el campo real_state
            $expenses = $expenses->filter(function ($expense) use ($filteredStates) {
                // Verificar que real_state esté en la lista de estados seleccionados
                return in_array($expense->real_state, $filteredStates);
            });
        }

        // Convertir la colección a array antes de enviarla en la respuesta JSON
        return response()->json(["expenses" => $expenses->values()->toArray()], 200);
    }


    public function storeExpense(HuaweiMonthlyExpenseRequest $request)
    {
        $data = $request->validated();
        $data['zone'] = HuaweiProject::find($data['huawei_project_id'])->huawei_site->name ?? 'Sin zona';
        $expense = HuaweiMonthlyExpense::create($data);

        $expenseDirectory = 'documents/huawei/monthly_expenses/';
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
        if ($expense->huawei_project_id) {
            $expense->load('huawei_project');
        }
        return response()->json($expense, 200);
    }

    public function updateExpense(HuaweiMonthlyExpense $expense, HuaweiMonthlyExpenseRequest $request)
    {
        $data = $request->validated();
        $data['zone'] = HuaweiProject::find($data['huawei_project_id'])->huawei_site->name ?? 'Sin zona';

        $expenseDirectory = 'documents/huawei/monthly_expenses/';

        $imageFields = ['image1', 'image2', 'image3'];

        foreach ($imageFields as $index => $field) {
            if ($request->hasFile($field)) {
                if ($expense->$field) {
                    $oldPath = public_path($expenseDirectory . $expense->$field);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                $imageFile = $request->file($field);
                $filename = "expense_{$expense->id}_" . ($index + 1) . '_' . time() . '.' . $imageFile->getClientOriginalExtension();
                $imageFile->move(public_path('documents/huawei/monthly_expenses/'), $filename);
                $data[$field] = $filename;
            } else {
                $data[$field] = $expense->$field;
            }
        }

        $expense->update($data);
        if ($expense->huawei_project_id) {
            $expense->load('huawei_project');
        }
        return response()->json($expense, 200);
    }

    public function showImage(HuaweiMonthlyExpense $expense, $image)
    {
        $field = 'image' . $image;
        $imageToShow = $expense->$field;
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
        $imageFields = ['image1', 'image2', 'image3'];

        foreach ($imageFields as $index => $field) {
            if ($expense->$field) {
                $oldPath = public_path($expenseDirectory . $expense->$field);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
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

        return response()->json([$expense->load('huawei_project.huawei_site')], 200);
    }

    public function exportMonthlyExpenses(HuaweiMonthlyProject $project)
    {
        return Excel::download(new HuaweiMonthlyExport($project->id), 'Gastos del proyecto ' . $project->description . '.xlsx');
    }

    public function importCosts(HuaweiMonthlyProject $monthly_project, Request $request)
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
        $endCell = 'O' . $sheet->getHighestRow();
        $range = "$startCell:$endCell";

        // Leer el rango especificado
        $data = $sheet->rangeToArray($range, null, true, true, true);

        // Array para almacenar los objetos
        $rowsAsObjects = [];

        // Recorrer las filas y convertir a objetos

        foreach ($data as $index => $row) {

            if ($index === 1)
                continue;

            $rowObject = (object) [
                'employee' => $this->sanitizeText($row['A'], true),
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
                'refund_status' => $this->sanitizeText($row['O'], false),
            ];

            $rowsAsObjects[] = $rowObject;
        }

        DB::beginTransaction();
        foreach ($rowsAsObjects as $item) {
            
            if (!empty($item->expense_date)) {
                try {
                    $expenseDate = Carbon::parse($item->expense_date);
            
                    $year = Carbon::parse($monthly_project->date)->year;
                    $month = Carbon::parse($monthly_project->date)->month;
            
                    $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfDay();
                    $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay();
            
                    if ($expenseDate->lt($startOfMonth) || $expenseDate->gt($endOfMonth)) {
                        DB::rollBack();
                        abort(400, 'La fecha del gasto está fuera del mes del proyecto.');
                    }
                } catch (\Exception $e) {
                    DB::rollBack();
                    abort(400, 'Formato de fecha inválido.');
                }
            } else {
                DB::rollBack();
                abort(400, 'Fecha de gasto requerida.');
            }
            
            if (!$item->expense_type || !$item->expense_date || !$item->cdp_type || !$item->amount || !$item->description) {
                DB::rollBack();
                abort(403, 'Llene los campos obligatorios');
            }

            if (!$item->refund_status){
                $item->refund_status = 'PENDIENTE';
            }

            HuaweiMonthlyExpense::create([
                'expense_type' => $item->expense_type,
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
                'refund_status' => $item->refund_status,
                'huawei_monthly_project_id' => $monthly_project->id,
                'huawei_project_id' => $item->project_id,
            ]);
        }
        DB::commit();
        return redirect()->back();
    }

    public function downloadTemplate ()
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

            return preg_replace('/\s+/', '', $text);
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
