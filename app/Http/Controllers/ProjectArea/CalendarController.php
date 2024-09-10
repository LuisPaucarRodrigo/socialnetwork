<?php

namespace App\Http\Controllers\ProjectArea;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Vacation;
use App\Models\Tasks;

class CalendarController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return Inertia::render('ProjectArea/Calendar/ProjectsCalendar', [
            'projects' => $projects,
        ]);
    }

    public function index_info_additional(){
        $vacations = Vacation::with('employee')->paginate();
        return Inertia::render('HumanResource/ManagementVacation/VacationInformation', [
            'vacations' => $vacations,
            'employees' => Employee::all()
        ]);
    }

    public function show(Project $project)
    {
        $project = Project::with('tasks')->find($project->id);
        if (!$project) {
            throw new \Exception('Proyecto no encontrado');
        }

        return Inertia::render('ProjectArea/Calendar/TasksCalendar', [
            'project' => $project,
        ]);
    }

}
