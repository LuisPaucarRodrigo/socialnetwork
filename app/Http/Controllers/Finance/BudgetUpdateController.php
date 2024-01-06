<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\BudgetUpdate;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class BudgetUpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Project $project)
    {
        $budget_updates = BudgetUpdate::where('project_id', $project->id)
            ->with('project')
            ->with('user')
            ->paginate();
        return Inertia::render('Finance/Budget/BudgetUpdates', [
            'budgetUpdates' => $budget_updates,
        ]);
    }

    public function create(Request $request, Project $project){
        $user_id = Auth::id();  
        $request->validate([
            'new_budget' => 'required',
            'project_id' => 'required',
            'reason' => 'required',
            'update_date' => 'required',
            'approved_update_date' => 'required',
        ]);
        $budget_update = BudgetUpdate::create([
            'new_budget' => $request->new_budget,
            'project_id' => $request->project_id,
            'reason' => $request->reason,
            'update_date' => $request->update_date,
            'user_id' => $user_id,
            'approved_update_date' => $request->approved_update_date,    
        ]);

        return to_route('initialbudget.index', ['project' => $project->id]);
    }

    public function show(BudgetUpdate $budgetupdate)
    {
        $budgetupdate->load('user', 'project');

        return Inertia::render('Finance/Budget/BudgetUpdates', [
            'budgetUpdate' => $budgetupdate,
        ]);
    }

    public function initial(Project $project)
    {
        $budget_update = BudgetUpdate::where('project_id', $project->id)
            ->with('project')
            ->with('user')
            ->orderByDesc('id') // Ordenar por ID en orden descendente
            ->first();

        return Inertia::render('Finance/Budget/InitialBudget', [
            'project' => $project,
            'budgetUpdate' => $budget_update,
        ]);
    }

    public function defineInitialBudget(Project $project, Request $request)
    {
        $request->validate([
            'initial_budget' => 'required',
        ]);

        $project->update([
            'initial_budget' => $request->initial_budget,    
        ]);

        return to_route('initialbudget.index', ['project' => $project->id]);

    }

    public function selectProject()
    {
        return Inertia::render('Finance/Budget/SelectProject', [
            'projects' => Project::all()
        ]);
    }

}
