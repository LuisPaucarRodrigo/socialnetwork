<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectManagementController extends Controller
{
    public function index()
    {
        return Inertia::render('ProjectArea/ProjectManagement/Project', [
            'projects' => Project::paginate(8),
        ]);
    }

    public function project_create(Request $request, $project_id = null)
    {
        $sd = "";
        $numberOfProjects = null;
        if ($project_id) {
            $project = Project::with('employees')->find($project_id);
            $current_mes = date('m', strtotime($project->start_date));
            $current_anio = date('y', strtotime($project->start_date));
            if ($request->query('start_date')) {
                $sd = $request->query('start_date');
                $mes = date('m', strtotime($sd));
                $anio = date('Y', strtotime($sd));
                if ($current_mes != $mes && $current_anio != $anio) {
                    $numberOfProjects = Project::whereMonth('start_date', $mes)
                        ->whereYear('start_date', $anio)
                        ->count() + 1;
                }
            }
            return Inertia::render('ProjectArea/ProjectManagement/CreateProject', [
                'employees' => Employee::select('id', 'name', 'lastname')->get(),
                'start_date' => $sd,
                'numberOfProjects' => $numberOfProjects,
                'project'=> $project
            ]);
        }
        if ($request->query('start_date')) {
            $sd = $request->query('start_date');
            $mes = date('m', strtotime($sd));
            $anio = date('Y', strtotime($sd));
            $numberOfProjects = Project::whereMonth('start_date', $mes)
                ->whereYear('start_date', $anio)
                ->count();
        }
        return Inertia::render('ProjectArea/ProjectManagement/CreateProject', [
            'employees' => Employee::select('id', 'name', 'lastname')->get(),
            'start_date' => $sd,
            'numberOfProjects' => $numberOfProjects + 1
        ]);
    }
    public function project_store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'code' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'priority' => 'required',
            'description' => 'required',
        ]);
        if ($request->id){
            $project = Project::find($request->id);
            $project->update($data);
        }else {
            $project = Project::create($data);
            $employees = $request->input('employees');
            foreach ($employees as $employee) {
                $empId = $employee['employee'];
                $project->employees()->attach($empId['id'], ['charge' => $employee['charge']]);
            }
        }
        return redirect()->route('projectmanagement.index');
    }

    public function project_delete_employee ($pivot_id){
        $tablaPivote = 'project_employee';
        DB::table($tablaPivote)->where('id', $pivot_id)->delete();
        return redirect()->back();
    }
    public function project_add_employee (Request $request, $project_id){
        $project = Project::find($project_id);
        $project->employees()->attach($request->input('employee.id'), ['charge' => $request->input('charge')]);
        return redirect()->back();
    }

    public function project_destroy($project_id)
    {
        $project = Project::find($project_id);
        $project->delete();
        return redirect()->back();
    }




}
