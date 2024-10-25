<?php

namespace App\Http\Controllers\Huawei;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\HuaweiMonthlyProject;
use Illuminate\Http\Request;
use Inertia\Inertia;


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

    public function searchProjects ($request)
    {
        $searchTerm = strtolower($request);
        $query = HuaweiMonthlyProject::query();

        $query->whereRaw('LOWER(description) LIKE ?', ["%{$searchTerm}%"])->orderBy('created_at', 'desc')->get();

        return Inertia::render('Huawei/MonthlyProjects', [
            'projects' => $query,
            'employees' => Employee::select('id', 'name', 'lastname')->orderBy('name')->get()->makeHidden(['documents_about_to_expire', 'policy_about_to_expire', 'sctr_about_to_expire'])
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

}
