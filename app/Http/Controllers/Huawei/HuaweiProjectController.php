<?php

namespace App\Http\Controllers\Huawei;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\HuaweiProject;
use App\Models\HuaweiProjectEmployee;
use App\Models\HuaweiSite;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\BrandModel;
use App\Models\HuaweiEntry;
use App\Models\HuaweiEquipment;
use App\Models\HuaweiEntryDetail;
use App\Models\HuaweiEquipmentSerie;
use App\Models\HuaweiMaterial;
use App\Models\Brand;
use App\Models\HuaweiRefund;
use Illuminate\Validation\Rule;

class HuaweiProjectController extends Controller
{
    public function show ()
    {
        return Inertia::render('Huawei/Projects', [
            'projects' => HuaweiProject::paginate(10),
            'huawei_sites' => HuaweiSite::all()
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
        return Inertia::render('Huawei/ProjectForm', [
            'huawei_project' => $huawei_project->load('huawei_site', 'huawei_project_employees.employee'),
            'huawei_sites' => HuaweiSite::all(),
            'employees' => Employee::all()
        ]);
    }

    public function store (Request $request)
    {
        $request->validate([
            'name' => 'required',
            'huawei_site_id' => 'required',
            'description' => 'nullable',
            'employees' => 'nullable'
        ]);

        $project = HuaweiProject::create([
            'name' => $request->name,
            'huawei_site_id' => $request->huawei_site_id,
            'description' => $request->description
        ]);

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
        $data = $request->validate([
            'name' => 'required',
            'huawei_site_id' => 'required',
            'description' => 'nullable'
        ]);

        $huawei_project->update($data);

        return redirect()->back();
    }

    public function deleteEmployee (HuaweiProjectEmployee $id)
    {
        $id->delete();

        return redirect()->back();
    }

    public function add_employee ($huawei_project, Request $request)
    {
        HuaweiProjectEmployee::create([
            'huawei_project_id' => $huawei_project,
            'employee_id' => $request->employee['id'],
            'role' => $request->charge

        ]);
        return redirect()->back();
    }
}
