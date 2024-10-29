<?php

namespace App\Http\Controllers\Huawei;

use App\Exports\HuaweiMonthlyExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Huawei\HuaweiMonthlyExpenseRequest;
use App\Models\Employee;
use App\Models\HuaweiMonthlyExpense;
use App\Models\HuaweiMonthlyProject;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class HuaweiMonthlyController extends Controller
{
    public function getProjects ()
    {
        return Inertia::render('Huawei/MonthlyProjects', [
            'projects' => HuaweiMonthlyProject::orderBy('created_at', 'desc')
                ->with('huawei_monthly_employees')
                ->paginate(10),
            'employees' => Employee::select('id', 'name', 'lastname')->orderBy('name')->get()->makeHidden(['documents_about_to_expire', 'policy_about_to_expire', 'sctr_about_to_expire'])
        ]);
    }

    public function searchProjects($searchTerm)
    {
        $searchTerm = strtolower($searchTerm);
        $query = HuaweiMonthlyProject::query();

        $projects = $query->whereRaw('LOWER(description) LIKE ?', ["%{$searchTerm}%"])
            ->orderBy('created_at', 'desc')
            ->with('huawei_monthly_employees')
            ->get();

        return Inertia::render('Huawei/MonthlyProjects', [
            'projects' => $projects,
            'employees' => Employee::select('id', 'name', 'lastname')
                ->orderBy('name')
                ->get()
                ->makeHidden(['documents_about_to_expire', 'policy_about_to_expire', 'sctr_about_to_expire']),
            'search' => $searchTerm
        ]);
    }


    public function storeProject (Request $request)
    {
        $data = $request->validate([
            'date' => 'required',
            'description' => 'required',
            'employees' => 'array|nullable'
        ]);

        $project = HuaweiMonthlyProject::create($data);

        if (!empty($data['employees'])) {
            $employeeIds = collect($data['employees'])->pluck('id')->toArray();
            $project->huawei_monthly_employees()->sync($employeeIds);
        }

        return redirect()->back();
    }

    public function updateProject (HuaweiMonthlyProject $project, Request $request)
    {
        $data = $request->validate([
            'date' => 'required',
            'description' => 'required',
            'employees' => 'array|nullable'
        ]);

        $project->update($data);

        if (!empty($data['employees'])) {
            $employeeIds = collect($data['employees'])->pluck('id')->toArray();
            $project->huawei_monthly_employees()->sync($employeeIds);
        }

        return redirect()->back();
    }

    //expenses
    public function getExpenses (HuaweiMonthlyProject $project)
    {
        $expenses = HuaweiMonthlyExpense::where('huawei_monthly_project_id', $project->id)->orderBy('created_at', 'desc')->paginate(15);
        $project->load(['huawei_monthly_employees' => function ($query) {
            $query->select('employees.id', 'employees.name', 'employees.lastname'); // Selecciona solo los campos necesarios
        }]);
        return Inertia::render('Huawei/MonthlyExpenses', [
            'expense' => $expenses,
            'project' => $project
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
                    ->orWhereRaw('LOWER(description) LIKE ?', ["%{$searchTerm}%"]);
            });

        // Ejecutar la consulta y obtener los resultados
        $expenses = $expensesQuery->orderBy('created_at', 'desc')->get();
        $project->load(['huawei_monthly_employees' => function ($query) {
            $query->select('employees.id', 'employees.name', 'employees.lastname'); // Selecciona solo los campos necesarios
        }]);
        return Inertia::render('Huawei/MonthlyExpenses', [
            'expense' => $expenses,
            'project' => $project,
            'search' => $request
        ]);
    }

    public function searchAdvance (HuaweiMonthlyProject $project, Request $request)
    {
        $expenses = HuaweiMonthlyExpense::where('huawei_monthly_project_id', $project->id);
        $employeeCount = $project->huawei_monthly_employees()->count();
        if (count($request->selectedZones) < 17){
            $expenses->whereIn('zone', $request->selectedZones);
        }
        if (count($request->selectedExpenseTypes) < 8){
            $expenses->whereIn('expense_type', $request->selectedExpenseTypes);
        }
        if (count($request->selectedCDPTypes) < 6){
            $expenses->whereIn('cdp_type', $request->selectedCDPTypes);
        }
        if (count($request->selectedEmployees) < $employeeCount){
            $expenses->whereIn('employee', $request->selectedEmployees);
        }
        $expenses = $expenses->orderBy('created_at', 'desc')->get(); // Asegúrate de asignar el resultado
        return response()->json(["expenses" => $expenses], 200);
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

        return response()->noContent();
    }

    public function exportMonthlyExpenses (HuaweiMonthlyProject $project)
    {
        return Excel::download(new HuaweiMonthlyExport($project->id), 'Gastos del proyecto ' . $project->description . '.xlsx');
    }
}
