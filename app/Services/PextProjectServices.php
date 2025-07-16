<?php

namespace App\Services;

use App\Models\CicsaAssignation;
use App\Models\CostLine;
use App\Models\PextProjectExpense;
use App\Models\Preproject;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class PextProjectServices
{
    public function getProject($status)
    {
        $project = Project::with('cost_center')->where('cost_line_id', 2)
            ->where('status', $status)
            ->whereHas('cost_center', function ($costCenterQuery) {
                $costCenterQuery->where(function ($query) {
                    $query->where('name', 'like', "%Mantto%")
                        ->orWhere('name', 'like', "%INDRA%");
                });
            })->whereHas('preproject')
            ->orderBy('created_at', 'desc');
        return $project;
    }

    public function searchProjectMonthly($status, $searchQuery): Object
    {
        $project = $this->getProject($status);
        $project = $project->where('description', 'like', "%$searchQuery%")->get();
        return $project;
    }

    public function getProjectOrProject(): Object
    {
        $pro = Preproject::where('cost_line_id', 2)
            ->where('status', 1)
            ->whereHas('cost_center', function ($query) {
                $query->where('name', 'not like', "%Mantto%");
            })
            ->whereDoesntHave('project')
            ->get();
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

    public function baseSearch($fixedOrAdditional)
    {
        $expense = PextProjectExpense::with(['provider:id,company_name'])
            ->where('fixedOrAdditional', $fixedOrAdditional);
        return $expense;
    }

    public function differentialSearch($expense, $project_id)
    {
        $expense->where('project_id', $project_id);
        return $expense;
    }

    public function differentialSearchMonthly($expense, $type)
    {
        $expense->whereHas('project', function ($query) use ($type) {
            $query->where('cost_line_id', $type)
                ->where('id', '!=', 320)
                ->whereDoesntHave('preproject');
        });
        return $expense;
    }

    public function rejectedSearch($expense, $rejected)
    {
        $expense = !$rejected ? $expense->where('is_accepted', 0)
            : $expense->where(function ($query) {
                $query->where('is_accepted', 1)
                    ->orWhere('is_accepted', null);
            });
        return $expense;
    }

    public function textSearch($expense, $searchTerms)
    {
        $expense = $expense->where(function ($query) use ($searchTerms) {
            $query->where('ruc', 'like', "%$searchTerms%")
                ->orWhere('doc_date', 'like', "%$searchTerms%")
                ->orWhere('description', 'like', "%$searchTerms%")
                ->orWhere('amount', 'like', "%$searchTerms%")
                ->orWhere('doc_number', 'like', "%$searchTerms%")
                ->orWhere('operation_number', 'like', "%$searchTerms%");
        });
        return $expense;
    }

    public function filterAdvance($expense, $request)
    {
        if (filter_var($request->docNoDate, FILTER_VALIDATE_BOOLEAN)) {
            $expense->where('doc_date', null);
        }

        if ($request->docStartDate) {
            $expense->where('doc_date', '>=', $request->docStartDate);
        }

        if ($request->docEndDate) {
            $expense->where('doc_date', '<=', $request->docEndDate);
        }

        if (filter_var($request->opNoDate, FILTER_VALIDATE_BOOLEAN)) {
            $expense->where('operation_date', null);
        }

        if ($request->opStartDate) {
            $expense->where('operation_date', '>=', $request->opStartDate);
        }

        if ($request->opEndDate) {
            $expense->where('operation_date', '<=', $request->opEndDate);
        }
        
        if ($request->selectedZones && count($request->selectedZones) < 9) {
            $expense->whereIn('zone', $request->selectedZones);
        }

        if (count($request->selectedExpenseTypes) < 14) {
            $expense->whereIn('expense_type', $request->selectedExpenseTypes);
        }

        if (count($request->selectedDocTypes) < 6) {
            $expense->whereIn('type_doc', $request->selectedDocTypes);
        }
        $expense->orderBy('created_at', 'desc');
        return $expense;
    }

    public function addCalculatedFields($expense)
    {
        $expense->each(function ($item) {
            $item->setAppends(['real_amount', 'real_state']);
        });
        return $expense;
    }

    public function filterCalculatedFields($expense, $selectedStateTypes)
    {
        if (count($selectedStateTypes) < 3) {
            $expense = $expense->filter(function ($item) use ($selectedStateTypes) {
                return in_array($item->real_state, $selectedStateTypes);
            })->values()->all();
        }
        return $expense;
    }

    public function getCostLine(String $cost_line_id): Object
    {
        $cost_line = CostLine::with('cost_center')->find($cost_line_id);
        return $cost_line;
    }

    public function expenseBars($project_id, $fixedOrAdditional): Builder
    {
        $bars = PextProjectExpense::where('project_id', $project_id)
            ->where('fixedOrAdditional', $fixedOrAdditional)
            ->select('expense_type', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('expense_type');
        return $bars;
    }

    public function baseAssignation($cost_line_id, $cost_center, $is_accepted): Builder
    {
        $project = CicsaAssignation::with('project.cost_center', 'project.project_quote.project_quote_valuations')
            ->whereHas('project', function ($query) use ($cost_line_id, $cost_center, $is_accepted) {
                $query->where('cost_line_id', $cost_line_id)
                    ->whereIn('cost_center_id', $cost_center)
                    ->where('is_accepted', $is_accepted);
            })
            ->orderBy('created_at', 'desc');
        return $project;
    }

    public function searchBase($project, $searchQuery): Builder
    {
        $project->where(function ($query) use ($searchQuery) {
            $query->where('project_name', 'like', "%$searchQuery%")
                ->orWhere('project_code', 'like', "%$searchQuery%")
                ->orWhere('cpe', 'like', "%$searchQuery%");
        });
        return $project;
    }

    public function costCenter(int $type): Object
    {
        $cost_centers = $type == 2 ? [6, 7, 8, 9] : [3];
        $cost_line = CostLine::with(['cost_center' => function ($query) use ($cost_centers) {
            $query->whereIn('id', $cost_centers);
        }])->find($type);
        return $cost_line;
    }

    public function addCalculated($project)
    {
        $project->each(function ($item) {
            $item->setAppends([
                'cicsa_charge_status',
            ]);
        });
        return $project;
    }

    public function index_additional_base(int $type, int $is_accepted): Builder
    {
        if ($type == 2) {
            $project = $this->baseAssignation(2, [6, 7, 8, 9], $is_accepted);
            $project = $project->whereDoesntHave('project.preproject');
        }

        if ($type == 1) {
            $project = $this->baseAssignation(1, [3], $is_accepted);
        }
        return $project;
    }

    public function project_expenses_base(int $project_id, $fixedOrAdditional): Builder
    {
        $expense = PextProjectExpense::with(['provider:id,company_name'])
            ->where('fixedOrAdditional', json_decode($fixedOrAdditional))
            ->where('project_id', $project_id)
            ->where(function ($query) {
                $query->where('is_accepted', 1)
                    ->orWhere('is_accepted', null);
            })
            ->orderBy('created_at', 'desc');
        return $expense;
    }

    public function expense_calculation($project_id, $fixedOrAdditional)
    {
        $project = Project::find($project_id);
        $result = $project
            ->pext_project_expenses()
            ->where(function ($query) {
                $query->where('is_accepted', 1)
                    ->orWhere('is_accepted', null);
            })
            ->where('fixedOrAdditional', $fixedOrAdditional)
            ->select('expense_type', DB::raw('SUM(amount/(1+igv/100)) as total_amount'))
            ->groupBy('expense_type');
        return $result;
    }

    public function map_expenses($item)
    {
        $item = $item->map(function ($cost) {
            return [
                'expense_type' => $cost->expense_type,
                'total_amount' => $cost->total_amount,
            ];
        })->toArray();
        return $item;
    }

    public function add_calculated_field($expense)
    {
        $expense->each(function ($exp) {
            $exp->setAppends(['real_amount', 'real_state']);
        });
        return $expense;
    }
}
