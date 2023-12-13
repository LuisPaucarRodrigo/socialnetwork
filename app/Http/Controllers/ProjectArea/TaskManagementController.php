<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Tasks;
use Inertia\Inertia;

class TaskManagementController extends Controller
{
    public function index()
    {
        return Inertia::render('ProjectArea/TasksManagement/index', [
            'projects' => Project::all(),
            'tasks' => Tasks::all(),
        ]);
    }

    public function new()
    {
        return Inertia::render('ProjectArea/TasksManagement/newTask',[
            'projects' => Project::all(),
        ]);
    }
    public function edit()
    {
        return Inertia::render('ProjectArea/TasksManagement/editTask');
    }
    public function create(Request $request)
    {
        $task = Tasks::create($request->validate(Tasks::$rules));
        return redirect()->route('tasks.index');
    }
}
