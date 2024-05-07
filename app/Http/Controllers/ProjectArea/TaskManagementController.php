<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest\CreateTaskCommentsRequest;
use App\Http\Requests\TaskRequest\CreateTaskRequest;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Tasks;
use App\Models\TaskComments;
use App\Models\ProjectEmployee;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
class TaskManagementController extends Controller
{
    public function index($id = null)
    {
        $projectId = $id;
        $tasks = Tasks::where('project_id', $projectId)->paginate(10);
        return Inertia::render('ProjectArea/TasksManagement/index', [
            'tasks' => $tasks,
            'project' => Project::find($projectId),
            'projects' => Project::where('id', '!=', $projectId)
                ->get()
        ]);
    }

    public function new($project_id)
    {
        return Inertia::render('ProjectArea/TasksManagement/newTask', [
            'project' => Project::find($project_id),
            'tasks' => Tasks::all(),
        ]);
    }

    public function edit($taskId)
    {
        $task = Tasks::find($taskId);
        $employeesToAssign = ProjectEmployee::with('employee_information')
            ->where('project_id', $task->project_id)->get();
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

    public function task_edit_date(Request $request)
    {
        $project = Project::find($request->project_id);
        $data = $request->validate([
            'id' => 'required|numeric',
            'start_date' => ['required', 'date', function ($attribute, $value, $fail) use ($project) {
                $startDate = Carbon::parse($value)->startOfDay();
                $projectStartDate = Carbon::createFromFormat('d/m/Y', $project->start_date)->startOfDay();
                $projectEndDate = Carbon::createFromFormat('d/m/Y', $project->end_date)->startOfDay();

                if ($startDate > $projectEndDate) {
                    $fail(__('La fecha de inicio debe ser inferior o igual a la fecha de fin del proyecto'));
                } else if ($startDate < $projectStartDate) {
                    $fail(__('La fecha de inicio debe ser mayor o igual a la fecha de inicio del proyecto'));
                }
            }],
            'end_date' => ['required', 'date', 'after_or_equal:start_date', function ($attribute, $value, $fail) use ($project) {
                $endDate = Carbon::parse($value)->startOfDay();
                $projectStartDate = Carbon::createFromFormat('d/m/Y', $project->start_date)->startOfDay();
                $projectEndDate = Carbon::createFromFormat('d/m/Y', $project->end_date)->startOfDay();

                if ($endDate > $projectEndDate) {
                    $fail(__('La fecha de fin debe ser inferior o igual a la fecha de fin del proyecto'));
                } else if ($endDate < $projectStartDate) {
                    $fail(__('La fecha de fin debe ser mayor o igual a la fecha de inicio del proyecto'));
                }
            }],
        ]);
        $tasks = Tasks::find($data['id']);
        $tasks->update($data);
    }

    public function task_duplicated(Request $request)
    {
        $request->validate([
            'project_id_duplicated' => 'required|numeric',
            'project_id' => 'required|numeric'
        ]);
        $originalTasks = Tasks::where('project_id', $request->project_id)->get();

        foreach ($originalTasks as $task) {
            $duplicatedTask = $task->replicate();
            $duplicatedTask->project_id = $request->project_id_duplicated;
            $duplicatedTask->status = "pendiente";
            $duplicatedTask->save();
        }
    }
}
