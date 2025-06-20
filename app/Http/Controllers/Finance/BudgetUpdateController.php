<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\BudgetUpdate;
use App\Models\CostLine;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class BudgetUpdateController extends Controller
{

    public function store(Request $request, Project $project)
    {
        $user_id = Auth::id();
        $user = Auth::user();
        $request->validate([
            'new_budget' => 'required',
            'project_id' => 'required',
            'reason' => 'required',
            'difference' => 'required',
        ]);

        BudgetUpdate::create([
            'new_budget' => $request->new_budget,
            'difference' => $request->difference,
            'project_id' => $request->project_id,
            'reason' => $request->reason,
            'user_id' => $user_id,
            'user_name' => $user->name . ' ' . $user->lastname
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

        $budget_updates = BudgetUpdate::where('project_id', $project->id)
            ->with('project')
            ->with('user')
            ->paginate(5);

        return Inertia::render('Finance/Budget/InitialBudget', [
            'project' => $project,
            'budgetUpdate' => $budget_update,
            'budgetUpdates' => $budget_updates
        ]);
    }

    public function selectProject()
    {
        $projects = Project::where('status', null)->select(
            'id',
            'description',
            'status',
            'preproject_id',
            'cost_center_id',
        )
            ->where(function ($query) {
                $query->whereIn('cost_center_id', [1])
                    ->orWhere(function ($subQuery) {
                        $subQuery->whereIn('cost_center_id', [4, 5, 6, 7, 8,12])
                            ->whereHas('preproject', function ($item) {
                                $item->select('id')
                                    ->where('status', 1);
                            });
                    });
            })
            ->get();
        // dd($projects);
        return Inertia::render('Finance/Budget/SelectProject', [
            // 'projects' => Project::where('status', null)->whereIn('cost_center_id', [1,4,5])->get(),
            'projects' => $projects,
            'costLines' => CostLine::all(),
        ]);
    }

    public function define_initial_budget(Request $request, Project $project)
    {
        $request->validate([
            'initial_budget' => 'required'
        ]);

        $project->update([
            'initial_budget' => $request->initial_budget // Corregido el nombre del campo
        ]);
        return to_route('initialbudget.index', ['project' => $project->id]);
    }
}
