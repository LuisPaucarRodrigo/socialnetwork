<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest\CreateTaskCommentsRequest;
use App\Http\Requests\TaskRequest\CreateTaskRequest;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Tasks;
use App\Models\TaskComments;
use App\Models\TaskEmployee;
use App\Models\Employee;
use App\Models\ProjectEmployee;
use Inertia\Inertia;


class TaskManagementController extends Controller
{
    public function index($id = null){
        $projectId = $id;
        $tasks = Tasks::where('project_id', $projectId)->paginate(5);
        return Inertia::render('ProjectArea/TasksManagement/index', [
            'projects' => Project::all(),
            'tasks' => $tasks,
            'project_id' => $projectId,
        ]);
    }


    public function new(){   
        return Inertia::render('ProjectArea/TasksManagement/newTask', [
            'projects' => Project::all(),
            'tasks' => Tasks::all(),
        ]);
    }


    public function edit($taskId){
        $task = Tasks::find($taskId);
        $employeesToAssign = ProjectEmployee::with('employee_information')
            ->where('project_id',$task->project_id)->get();
        $comment = TaskComments::where('task_id', $taskId)->get();
        $added_employees = Tasks::with('project_employee', 'project_employee.employee_information' . '')->where('id', $taskId)->first();
        return Inertia::render('ProjectArea/TasksManagement/editTask', [
            'tasks' => $task,
            'comments' => $comment,
            'employeesToAssign' => $employeesToAssign,
            'added_employees' => $added_employees,
        ]);
    }

    public function create(CreateTaskRequest $request)
    {
        $task = Tasks::create($request->validated());
        $projectId = $task->project_id;
        return redirect()->route('tasks.index', $projectId);
    }

    public function comment(CreateTaskCommentsRequest $request)
    {
        TaskComments::create($request->validated());
        return back();
    }

    public function add_employee(Request $request)
    {
        $projectEmployee = ProjectEmployee::find($request->project_employee_id);
        $projectEmployee->tasks()->attach($request->task_id);
        return back();
    }

    public function delete_employee(Request $request)
    {
        //dd($request);
        $projectEmployee = ProjectEmployee::find($request->project_employee_id);
        $task = Tasks::find($request->task_id);
        $projectEmployee->tasks()->detach($task);
        return back();
    }

    public function status_task($taskId, $status)
    {
        $task = Tasks::find($taskId);
        if ($status === 'start') {
            $status = "proceso";
        } elseif ($status === 'stop') {
            $status = "detenido";
        } else {
            $status = "completado";
        }
        $task->update(['status' => $status]);
        return back();
    }

    public function delete_task($taskId)
    {
        $task = Tasks::find($taskId);
        $projectId = $task->project_id;
        $task->delete();
        return redirect()->route('tasks.index', $projectId);
    }
}
