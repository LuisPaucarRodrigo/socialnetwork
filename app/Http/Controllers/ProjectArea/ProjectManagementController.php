<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest\CreateProjectRequest;
use App\Models\Employee;
use App\Models\Project;
use App\Models\BudgetUpdate;
use App\Models\Resource;
use App\Models\ProjectResource;
use App\Models\Purchasing_request;
use App\Models\Purchase_quote;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectManagementController extends Controller
{
    public function index()
    {
        return Inertia::render('ProjectArea/ProjectManagement/Project', [
            'projects' => Project::paginate(),
        ]);
    }

    public function project_create(Request $request, $project_id = null)
    {
        if ($project_id) {
            $project = Project::with('employees')->find($project_id);
            return Inertia::render('ProjectArea/ProjectManagement/CreateProject', [
                'employees' => Employee::select('id', 'name', 'lastname')->get(),
                'project' => $project,
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
        ]);
    }

    public function project_store(CreateProjectRequest $request)
    {
        $data = $request->validated();

        $data['initial_budget'] = 0;

        if ($request->id) {
            $project = Project::find($request->id);
            $project->update($data);
        } else {
            $project = Project::create($data);
            $employees = $request->input('employees');
            foreach ($employees as $employee) {
                $empId = $employee['employee'];
                $project->employees()->attach($empId['id'], ['charge' => $employee['charge']]);
            }
        }
        return redirect()->route('projectmanagement.index');
    }

    public function project_delete_employee($pivot_id)
    {
        $tablaPivote = 'project_employee';
        DB::table($tablaPivote)->where('id', $pivot_id)->delete();
        return redirect()->back();
    }

    public function project_add_employee(Request $request, $project_id)
    {
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

    public function project_resources($project_id)
    {
        $project = Project::with('resources')->find($project_id);
        $resources = Resource::all();

        $resourcesDisponibles = $resources->filter(function ($resource) {
            return $resource->state === 'Disponible';
        });
        return Inertia::render('ProjectArea/ProjectManagement/ResourcesAssignment', [
            'project' => $project,
            'resources' => $resourcesDisponibles,
        ]);
    }

    public function project_resources_store(Request $request)
    {
        $resource = Resource::find($request->resource_id);
        if ($resource->quantity < $request->quantity) {
            return response()->json(['error' => 'Cantidad excedida, recarga la página'], 500);
        }
        ProjectResource::create($request->all());
        return redirect()->back();
    }
    
    public function project_resources_delete($resource_id)
    {
        $resource = ProjectResource::find($resource_id);
        $resource->delete();
        return redirect()->back();
    }

    public function project_purchases_request_index($project_id)
    {
        $purchases = Purchasing_request::where('project_id', $project_id)->paginate();
        return Inertia::render('ProjectArea/ProjectManagement/PurchaseRequest',[
            'purchases'=> $purchases,
            'project_id'=> $project_id,
        ]);
    }

    public function project_purchases_request_create($project_id, $purchase_id = null) {
        if ($purchase_id){
            $purchase_request = Purchasing_request::find($purchase_id);
            return Inertia::render('ProjectArea/ProjectManagement/CreatePurchaseRequest', [
                'project_id'=>$project_id,
                'purchase_request'=>$purchase_request
            ]);
        }
        return Inertia::render('ProjectArea/ProjectManagement/CreatePurchaseRequest', [
            'project_id'=>$project_id
        ]);
    }

    public function project_purchases_request_store(Request $request) {
        $data = $request->validate([
            'title' => 'required',
            'product_description' => 'required',
            'due_date' => 'required',
            'project_id' => 'required',
        ]);
        if ($request->id) {
            $purchase_request = Purchasing_request::find($request->id);
            $purchase_request->update($data);
        } else { 
            Purchasing_request::create($data);
        }
        return redirect()->route('projectmanagement.purchases_request.index', ['project_id' => $request->project_id]);
    }

    public function project_expenses(Project $project_id) {

        $last_update = BudgetUpdate::where('project_id', $project_id->id)
            ->with('project')
            ->with('user')
            ->orderByDesc('id') // Ordenar por ID en orden descendente
            ->first();

        $current_budget = $last_update ? $last_update->new_budget : $project_id->initial_budget;

        $expenses = Purchasing_request::with('purchase_quotes.purchase_order')
            ->has('purchase_quotes.purchase_order')
            ->where([['project_id', $project_id->id],['state','Aceptado']])
            ->whereHas('purchase_quotes.purchase_order', function ($query) {
                $query->where('state', 'Completada');
            });

        $total_expenses = $expenses->get()->sum(function ($expense) {
            return $expense->purchase_quotes->amount;
        });

        $remaining_budget = $current_budget - $total_expenses;    

        return Inertia::render('ProjectArea/ProjectManagement/ProjectExpenses', [
            'current_budget' => $current_budget,
            'remaining_budget' => $remaining_budget,
            'expenses' => $expenses->paginate(),
        ]);
    }
}
