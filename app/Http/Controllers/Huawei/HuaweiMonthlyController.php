<?php

namespace App\Http\Controllers\Huawei;

use App\Exports\HuaweiMonthlyExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Huawei\HuaweiMonthlyExpenseRequest;
use App\Models\Employee;
use App\Models\HuaweiMonthlyExpense;
use App\Models\HuaweiMonthlyProject;
use App\Models\HuaweiProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class HuaweiMonthlyController extends Controller
{
    public function getProjects ()
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


    public function storeProject (Request $request)
    {
        $data = $request->validate([
            'date' => 'required',
            'description' => 'required',
        ]);

        $project = HuaweiMonthlyProject::create($data);

        return redirect()->back();
    }

    public function updateProject (HuaweiMonthlyProject $project, Request $request)
    {
        $data = $request->validate([
            'date' => 'required',
            'description' => 'required',
        ]);

        $project->update($data);

        return redirect()->back();
    }

    //expenses
    public function getExpenses (HuaweiMonthlyProject $project)
    {
        $expenses = HuaweiMonthlyExpense::where('huawei_monthly_project_id', $project->id)->orderBy('expense_date')
            ->paginate(20);

        return Inertia::render('Huawei/MonthlyExpenses', [
            'expense' => $expenses,
            'project' => $project,
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
                    ->orWhereRaw('LOWER(ec_op_number) LIKE ?', ["%{$searchTerm}%"]);
            });

        // Ejecutar la consulta y obtener los resultados
        $expenses = $expensesQuery->orderBy('expense_date')->get();

        return Inertia::render('Huawei/MonthlyExpenses', [
            'expense' => $expenses,
            'project' => $project,
            'search' => $request,
        ]);
    }

    public function searchAdvance(HuaweiMonthlyProject $project, Request $request)
    {
        // Iniciar la consulta base de expenses
        $expensesQuery = HuaweiMonthlyExpense::where('huawei_monthly_project_id', $project->id);

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
        $expenses = $expensesQuery->orderBy('expense_date')->get();

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


    public function storeExpense (HuaweiMonthlyExpenseRequest $request)
    {
        $data = $request->validated();
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

        return redirect()->back();
    }

    public function updateExpense(HuaweiMonthlyExpense $expense, HuaweiMonthlyExpenseRequest $request)
    {
        $data = $request->validated();

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

        return redirect()->back()->with('success', 'Gasto actualizado correctamente y archivos procesados.');
    }

    public function showImage (HuaweiMonthlyExpense $expense, $image)
    {
        $field = 'image' . $image;
        $imageToShow = $expense->$field;
        $path = public_path("documents/huawei/monthly_expenses/$imageToShow");
        if (file_exists($path)){
            ob_end_clean();
            return response()->file($path);
        }
        abort(403, 'Imagen no encontrada');
    }

    public function deleteExpense (HuaweiMonthlyExpense $expense)
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
        return redirect()->back();
    }

    public function validateExpense (Request $request, HuaweiMonthlyExpense $expense)
    {
        $validatedData = $request->validate([
            'is_accepted' => 'required|boolean'
        ]);

        $expense->update($validatedData);

        return response()->json([$expense], 200);
    }

    public function exportMonthlyExpenses (HuaweiMonthlyProject $project)
    {
        return Excel::download(new HuaweiMonthlyExport($project->id), 'Gastos del proyecto ' . $project->description . '.xlsx');
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

        $updatedCosts = HuaweiMonthlyExpense::whereIn('id', $data['ids'])->get();

        return response()->json($updatedCosts ,200);
    }

    public function massiveValidate (Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
            'state' => 'required'
        ]);

        DB::beginTransaction();

        foreach ($data['ids'] as $item){
            $expense = HuaweiMonthlyExpense::find($item);
            if ($expense->is_accepted !== null){
                DB::rollBack();
                abort(403, 'Acción no permitida');
            }
            $expense->update([
                'is_accepted' => $data['state']
            ]);
        }
        DB::commit();
        $updatedCosts = HuaweiMonthlyExpense::whereIn('id', $data['ids'])
            ->get();

        return response()->json($updatedCosts, 200);
    }
}
