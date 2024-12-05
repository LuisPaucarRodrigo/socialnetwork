<?php

namespace App\Http\Controllers\ProjectArea;

use App\Exports\PextExpenseExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cicsa\StoreOrUpdateAssigantionRequest;
use App\Http\Requests\PextProjectRequest\StoreOrUpdateRequest;
use App\Models\AccountStatement;
use App\Models\CicsaAssignation;
use App\Models\CostLine;
use App\Models\PextProject;
use App\Models\PextProjectExpense;
use App\Models\Project;
use App\Models\Provider;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class PextController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('get')) {
            $cost_line = CostLine::where('name', 'PEXT')->with('cost_center')->first();
            $project = PextProject::orderBy('created_at', 'desc')->paginate();
            return Inertia::render(
                'ProjectArea/ProjectManagement/PextProject',
                [
                    'project' => $project,
                    'cost_line' => $cost_line
                ]
            );
        } else {
            $search = $request->searchQuery;
            $project = PextProject::where('description', 'like', "%$search%")
                ->orWhere('date', 'like', "%$search%")
                ->get();
            return response()->json($project, 200);
        }
    }

    public function requestCicsa($zone = null)
    {
        $cicsa = CicsaAssignation::select('id', 'project_name', 'customer')
            ->where('zone', $zone)
            ->orWhereHas('cicsa_charge_area', function ($subQuery) {
                $subQuery->where(function ($subQuery) {
                    $subQuery->whereNull('invoice_number')
                        ->orWhereNull('invoice_date')
                        ->orWhereNull('amount');
                })
                    ->whereNull('deposit_date');
            })
            ->get();
        return response()->json($cicsa, 200);
    }

    public function storeOrUpdate(Request $request, $pext_id = null)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'description' => 'required|string',
            'cost_line_id' => 'required|numeric',
            'cost_center_id' => 'required|numeric',
        ]);
        $project = Project::create([
            'priority' => 'Alta',
            'description' => $validatedData['description'],
            'cost_line_id' => $validatedData['cost_line_id'],
            'cost_center_id' => $validatedData['cost_center_id'],
        ]);
        $validatedData['project_id'] = $project->id;
        $projectPext = PextProject::updateOrCreate(
            ['id' => $pext_id],
            $validatedData
        );
        return response()->json($projectPext, 200);
    }

    public function delete(PextProject $pext)
    {
        $pext->delete();
        return response()->noContent();
    }

    public function export_expenses(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        return Excel::download(new PextExpenseExport(null, $startDate, $endDate), 'Gastos_Pext.xlsx');
    }

    public function index_expenses(Request $request, $pext_project_id)
    {
        if ($request->isMethod('get')) {
            $expense = PextProjectExpense::with(['provider:id,company_name', 'cicsa_assignation:id,project_name', 'pext_project.project.cost_center'])
                ->where('pext_project_id', $pext_project_id)
                ->where('is_accepted', 1)
                ->orWhere('is_accepted', null)
                ->orderBy('created_at', 'desc')
                ->paginate();
            foreach ($expense as $exp) {
                $exp->setAppends(['real_amount']);
                $exp->state = 1 ? true : false;
            }
            $providers = Provider::select('id', 'ruc', 'company_name')->get();
            $cost_line = CostLine::where('name', 'PEXT')->with('cost_center')->first();
            return Inertia::render(
                'ProjectArea/ProjectManagement/PextExpenses',
                [
                    'expense' => $expense,
                    'providers' => $providers,
                    'pext_project_id' => $pext_project_id,
                    'cost_center' => $cost_line->cost_center
                ]
            );
        } else {
            $rejected = $request->rejected;
            $expense = PextProjectExpense::with(['provider:id,company_name', 'cicsa_assignation:id,project_name', 'pext_project.project.cost_center'])
                ->where('pext_project_id', $pext_project_id)
                ->orderBy('created_at', 'desc');
            $expense = $request->state === false ? $expense->where('is_accepted', 0)
                : $expense->where(function ($query) use ($rejected) {
                    $query->where('is_accepted', $rejected)
                        ->orWhere('is_accepted', null);
                });

            if ($rejected) {
                $expense->where('is_accepted', $request->rejected);
            }
            if ($request->search) {
                $searchTerms = $request->input('search');
                $expense = $expense->where(function ($query) use ($searchTerms) {
                    $query->where('ruc', 'like', "%$searchTerms%")
                        ->orWhere('doc_date', 'like', "%$searchTerms%")
                        ->orWhere('description', 'like', "%$searchTerms%")
                        ->orWhere('amount', 'like', "%$searchTerms%");
                });
            }
            if (count($request->selectedCostCenter) < 6) {
                $costCenter = $request->selectedCostCenter;
                $expense = $expense->whereHas('pext_project.project.cost_center', function ($query) use ($costCenter) {
                    $query->whereIn('name', $costCenter);
                });
            }

            if (count($request->selectedZones) < 7) {
                $expense = $expense->whereIn('zone', $request->selectedZones);
            }
            if (count($request->selectedExpenseTypes) < 14) {
                $expense = $expense->whereIn('expense_type', $request->selectedExpenseTypes);
            }
            if (count($request->selectedDocTypes) < 5) {
                $expense = $expense->whereIn('type_doc', $request->selectedDocTypes);
            }
            $expense = $expense->orderBy('doc_date')->get();

            $expense->transform(function ($item) {
                $item->setAppends(['real_amount']);
                $item->state = 1 ? true : false;
                return $item;
            });

            return response()->json($expense, 200);
        }
    }

    public function expenses_storeOrUpdate(StoreOrUpdateRequest $request, $expense_id = null)
    {
        $validatedData = $request->validated();
        $validatedData['is_accepted'] = true;
        $validatedData['amount'] = floatval($validatedData['amount']);
        $validatedData['state'] = json_decode($validatedData['state']);

        $validatedData['account_statement_id'] = null;
        if (isset($validatedData['operation_number']) && isset($validatedData['operation_date'])) {
            $on = substr($validatedData['operation_number'], -6);
            $as = AccountStatement::where('operation_date', $validatedData['operation_date'])
                ->where('operation_number', $on)->first();
            $validatedData['account_statement_id'] = $as?->id;
        }

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $validatedData['photo'] = time() . '._' . $photo->getClientOriginalName();
            $photo->move(public_path('documents/expensesPext/'), $validatedData['photo']);
        }
        $expense = PextProjectExpense::updateOrCreate(
            ['id' => $expense_id],
            $validatedData
        );
        // $expense->with(['cicsa_assignation:id,project_name,customer']);
        $expense->load('cicsa_assignation.project.cost_center');
        $expense->setAppends(['real_amount']);
        return response()->json($expense, 200);
    }

    public function expenses_delete(PextProjectExpense $expense_id)
    {
        $expense_id->delete();
        return response()->noContent();
    }

    public function expense_show_image(PextProjectExpense $expense_id)
    {
        $fileName = $expense_id->photo;
        $filePath = '/documents/expensesPext/' . $fileName;
        $path = public_path($filePath);
        if (file_exists($path)) {
            ob_end_clean();
            return response()->file($path);
        }
        abort(404, 'Documento no encontrado');
    }

    public function expense_validate(Request $request, $expense_id)
    {
        $validatedData = $request->validate([
            'is_accepted' => 'required|boolean'
        ]);
        $expense = PextProjectExpense::find($expense_id);
        $expense->update($validatedData);

        return response()->noContent();
    }

    public function expense_export($pext_project_id)
    {
        return Excel::download(new PextExpenseExport($pext_project_id), 'Gastos_Pext.xlsx');
    }

    public function index_additional(Request $request)
    {
        if ($request->isMethod('get')) {
            $project = CicsaAssignation::orderBy('created_at', 'desc')->paginate();
            $cost_line = CostLine::where('name', 'Pext')->with('cost_center')->first();
            return Inertia::render('ProjectArea/ProjectManagement/PextProjectAdditional', [
                'project' => $project,
                'cost_line' => $cost_line
            ]);
        } elseif ($request->isMethod('post')) {
            $project = CicsaAssignation::orWhere('project_name', 'like', "%$request->searchQuery%")
                ->orWhere('project_code', 'like', "%$request->searchQuery%")
                ->orWhere('cpe', 'like', "%$request->searchQuery%")
                ->get();
            return response()->json($project, 200);
        }
    }

    public function updateOrStoreAdditional(StoreOrUpdateAssigantionRequest $request, $cicsa_assignation_id = null)
    {
        $validateData = $request->validated();
        $project = Project::create([
            'priority' => 'Alta',
            'description' => $validateData['project_name'],
            'cost_line_id' => $validateData['cost_line_id'],
            'cost_center_id' => $validateData['cost_center_id']
        ]);
        $validateData['project_id'] = $project->id;
        $cicsa_assignation = CicsaAssignation::updateOrCreate(
            ['id' => $cicsa_assignation_id],
            $validateData
        );
        return response()->json($cicsa_assignation, 200);
    }

    public function additional_expense_index(Request $request, $cicsa_assignation_id)
    {
        if ($request->isMethod('get')) {
            $expense = PextProjectExpense::with(['provider:id,company_name', 'cicsa_assignation.project.cost_center'])
                ->where('cicsa_assignation_id', $cicsa_assignation_id)
                ->where('is_accepted', 1)
                ->orWhere('is_accepted', null)
                ->orderBy('created_at', 'desc')
                ->paginate();
            foreach ($expense as $exp) {
                $exp->setAppends(['real_amount']);
                $exp->state = 1 ? true : false;
            }
            $providers = Provider::select('id', 'ruc', 'company_name')->get();
            $cost_line = CostLine::where('name', 'PEXT')->with('cost_center')->first();
            return Inertia::render(
                'ProjectArea/ProjectManagement/PextProjectAdditionalExpenses',
                [
                    'expense' => $expense,
                    'providers' => $providers,
                    'cicsa_assignation_id' => $cicsa_assignation_id,
                    'cost_center' => $cost_line->cost_center
                ]
            );
        } else {
            $rejected = $request->rejected;
            $expense = PextProjectExpense::with(['provider:id,company_name', 'cicsa_assignation.project.cost_center'])
                ->where('cicsa_assignation_id', $cicsa_assignation_id)
                ->orderBy('created_at', 'desc');
            $expense = $request->state === false ? $expense->where('is_accepted', 0)
                : $expense->where(function ($query) use ($rejected) {
                    $query->where('is_accepted', $rejected)
                        ->orWhere('is_accepted', null);
                });

            if ($rejected) {
                $expense->where('is_accepted', $request->rejected);
            }
            if ($request->search) {
                $searchTerms = $request->input('search');
                $expense = $expense->where(function ($query) use ($searchTerms) {
                    $query->where('ruc', 'like', "%$searchTerms%")
                        ->orWhere('doc_date', 'like', "%$searchTerms%")
                        ->orWhere('description', 'like', "%$searchTerms%")
                        ->orWhere('amount', 'like', "%$searchTerms%");
                });
            }
            if (count($request->selectedCostCenter) < 6) {
                $costCenter = $request->selectedCostCenter;
                $expense = $expense->whereHas('cicsa_assignation.project.cost_center', function ($query) use ($costCenter) {
                    $query->whereIn('name', $costCenter);
                });
            }

            if (count($request->selectedZones) < 7) {
                $expense = $expense->whereIn('zone', $request->selectedZones);
            }
            if (count($request->selectedExpenseTypes) < 14) {
                $expense = $expense->whereIn('expense_type', $request->selectedExpenseTypes);
            }
            if (count($request->selectedDocTypes) < 5) {
                $expense = $expense->whereIn('type_doc', $request->selectedDocTypes);
            }
            $expense = $expense->orderBy('doc_date')->get();

            $expense->transform(function ($item) {
                $item->setAppends(['real_amount']);
                $item->state = 1 ? true : false;
                return $item;
            });

            return response()->json($expense, 200);
        }
    }
}
