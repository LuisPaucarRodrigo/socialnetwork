<?php

namespace App\Services;

use App\Models\CicsaAssignation;
use App\Models\PextProjectExpense;
use App\Models\Preproject;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use PhpParser\Node\Expr\Cast\Array_;

class PextProjectServices
{
    private function baseCicsaAssignation(): Builder
    {
        $query = CicsaAssignation::with('project.cost_center', 'project.preproject')
            ->whereHas('project', function ($query) {
                $query->where('cost_line_id', 2)
                    ->whereHas('cost_center', function ($costCenterQuery) {
                        $costCenterQuery->where('name', 'like', "%Mantto%");
                    })->whereHas('preproject');
            });
        return $query;
    }

    public function getCicsaAssignation()
    {
        $cicsaAssignation = $this->baseCicsaAssignation();
        $cicsaAssignation = $cicsaAssignation->orderBy('created_at', 'desc')
            ->paginate();
        return $cicsaAssignation;
    }

    public function searchCicsaAssignation($searchQuery): Object
    {
        $cicsaAssignation = $this->baseCicsaAssignation();
        $cicsaAssignation = $cicsaAssignation->where(function ($query) use ($searchQuery) {
            $query->orWhere('project_name', 'like', "%$searchQuery%")
                ->orWhere('project_code', 'like', "%$searchQuery%")
                ->orWhere('cpe', 'like', "%$searchQuery%");
        })
            ->orderBy('created_at', 'desc')
            ->get();
        return $cicsaAssignation;
    }

    public function getProjectOrProject($type): Object
    {
        if ($type === 'Proyectos') {
            $pro = Project::select('id', 'preproject_id', 'cost_line_id')
                ->with(['preproject:id,code'])
                ->whereNotNull('preproject_id')
                ->where('cost_line_id', 2)
                ->whereDoesntHave('cicsa_assignation')
                ->get();
        } else {
            $pro = Preproject::where('cost_line_id', 2)
                ->where('status', 1)
                ->whereHas('cost_center', function ($query) {
                    $query->where('name', 'not like', "%Mantto%");
                })
                ->whereDoesntHave('project')
                ->get();
        }
        $pro->each->setAppends([]);
        return $pro;
    }

    public function storeOrUpdateAssignation($validateData, $cicsa_assignation_id): Object
    {
        $cicsaAssignation = CicsaAssignation::updateOrCreate(
            ['id' => $cicsa_assignation_id],
            $validateData
        );

        $cicsaAssignation->load('project.cost_center');
        return $cicsaAssignation;
    }

    public function storeProject($validateData): array
    {
        $preproject = Preproject::find($validateData['pre_project_id']);
        $project = Project::create([
            'priority' => 'Alta',
            'description' => $validateData['project_name'],
            'cost_center_id' => $preproject->cost_center_id,
            'cost_line_id' => $preproject->cost_line_id,
            'preproject_id' => $validateData['pre_project_id']
        ]);
        $validateData['project_id'] = $project->id;
        return $validateData;
    }

    public function baseSearch($fixedOrAdditional): Builder
    {
        $expense = PextProjectExpense::with(['provider:id,company_name', 'project.cost_center'])
            ->where('fixedOrAdditional', $fixedOrAdditional);
        return $expense;
    }

    public function differentialSearch($expense, $project_id)
    {
        $expense->where('project_id', $project_id);
        return $expense;
    }

    public function rejectedSearch($expense, $rejected): Builder
    {
        $expense = !$rejected ? $expense->where('is_accepted', 0)
            : $expense->where(function ($query) {
                $query->where('is_accepted', 1)
                    ->orWhere('is_accepted', null);
            });
        return $expense;
    }

    public function textSearch($expense, $searchTerms): Builder
    {
        $expense = $expense->where(function ($query) use ($searchTerms) {
            $query->where('ruc', 'like', "%$searchTerms%")
                ->orWhere('doc_date', 'like', "%$searchTerms%")
                ->orWhere('description', 'like', "%$searchTerms%")
                ->orWhere('amount', 'like', "%$searchTerms%");
        });
        return $expense;
    }

    public function filterAdvance($expense, $request): Object
    {
        if ($request->docNoDate) {
            $expense->where('doc_date', null);
        }

        if ($request->docStartDate) {
            $expense->where('doc_date', '>=', $request->docStartDate);
        }

        if ($request->docEndDate) {
            $expense->where('doc_date', '<=', $request->docEndDate);
        }

        if ($request->opNoDate) {
            $expense->where('operation_date', null);
        }

        if ($request->opStartDate) {
            $expense->where('operation_date', '>=', $request->opStartDate);
        }

        if ($request->opEndDate) {
            $expense->where('operation_date', '<=', $request->opEndDate);
        }

        if ($request->selectedZones && count($request->selectedZones) < 6) {
            $expense = $expense->whereIn('zone', $request->selectedZones);
        }

        if (count($request->selectedExpenseTypes) < 14) {
            $expense = $expense->whereIn('expense_type', $request->selectedExpenseTypes);
        }

        if (count($request->selectedDocTypes) < 6) {
            $expense = $expense->whereIn('type_doc', $request->selectedDocTypes);
        }
        $expense = $expense->orderBy('doc_date')->get();
        return $expense;
    }

    public function addCalculatedFields($expense)
    {
        $expense->transform(function ($item) {
            $item->setAppends(['real_amount', 'real_state']);
            return $item;
        });
        return $expense;
    }

    public function filterCalculatedFields($expense, $selectedStateTypes)
    {
        if (count($selectedStateTypes)) {
            $expense = $expense->filter(function ($item) use ($selectedStateTypes) {
                return in_array($item->real_state, $selectedStateTypes);
            })->values()->all();
        }
        return $expense;
    }
}
