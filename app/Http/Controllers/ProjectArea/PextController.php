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
        $text = "Mantto";
        if ($request->isMethod('get')) {
            // $project = Project::with('cicsa_assignation', 'cost_center')->whereHas('cost_line', function ($query) use ($text) {
            //     $query->where('name', 'PEXT');
            // })->whereHas('cost_center', function ($query) use ($text) {
            //     $query->where('name', 'like', "%$text%");
            // })->orderBy('created_at', 'desc')->paginate();
            // $cost_line = CostLine::where('name', 'Pext')
            //     ->with(['cost_center' => function ($query) use ($text) {
            //         $query->where('name', 'like', "%$text%");
            //     }])
            //     ->first();
            // return Inertia::render('ProjectArea/ProjectManagement/PextProject', [
            //     'project' => $project,
            //     'cost_line' => $cost_line
            // ]);
            $cicsa_assignation = CicsaAssignation::with('project.cost_center')
                ->whereHas('project.cost_center', function ($query) use ($text) {
                    $query->where('name', 'like', "%$text%");
                })
                ->orderBy('created_at', 'desc')
                ->paginate();
            $project = Project::select('id', 'preproject_id')
                ->with(['preproject:id,code'])
                ->where('cost_line_id', 2)
                ->whereDoesntHave('cicsa_assignation')
                ->get();

            $project->each(function ($item) {
                $item->setAppends([]);
                if ($item->preproject) {
                    $item->preproject->setAppends([]);
                }
            });

            return Inertia::render('ProjectArea/ProjectManagement/PextProject', [
                'cicsa_assignation' => $cicsa_assignation,
                'project' => $project
            ]);
        } elseif ($request->isMethod('post')) {
            $searchQuery = $request->searchQuery;
            $project = CicsaAssignation::with('project.cost_center')
                ->whereHas('project.cost_center', function ($query) use ($text) {
                    $query->where('name', 'like', "%$text%");
                })->where(function ($query) use ($searchQuery) {
                    $query->orWhere('project_name', 'like', "%$searchQuery%")
                        ->orWhere('project_code', 'like', "%$searchQuery%")
                        ->orWhere('cpe', 'like', "%$searchQuery%");
                })
                ->orderBy('created_at', 'desc')
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

    public function storeOrUpdate(Request $request, $cicsa_assignation_id = null)
    {
        $validatedData = $request->validate([
            'assignation_date' => 'nullable|date',
            'project_name' => 'required|string',
            'customer' => 'nullable|string',
            'project_code' => 'nullable|string',
            'cpe' => 'nullable|string',
            'zone' => 'required|string',
            'zone2' => 'nullable|string',
            'manager' => 'required|string',
            'user_name' => 'required|string',
            'user_id' => 'required|numeric',
            'project_id' => 'required|numeric',
        ]);
        // $project = Project::create([
        //     'priority' => 'Alta',
        //     'description' => $validatedData['project_name'],
        //     'cost_line_id' => $validatedData['cost_line_id'],
        //     'cost_center_id' => $validatedData['cost_center_id'],
        // ]);
        $cicsaAssignation = CicsaAssignation::updateOrCreate(
            ['id' => $cicsa_assignation_id],
            $validatedData
        );
        $cicsaAssignation->load('project.cost_center');
        return response()->json($cicsaAssignation, 200);
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

    public function index_expenses(Request $request, $cicsa_assignation_id)
    {
        if ($request->isMethod('get')) {
            $expense = PextProjectExpense::with(['provider:id,company_name', 'cicsa_assignation.project.cost_center'])
                ->where('fixedOrAdditional', true)
                ->where('cicsa_assignation_id', $cicsa_assignation_id)
                ->where(function ($query) {
                    $query->where('is_accepted', 1)
                        ->orWhere('is_accepted', null);
                })
                ->orderBy('created_at', 'desc')
                ->paginate();
            foreach ($expense as $exp) {
                $exp->setAppends(['real_amount']);
                $exp->state = 1 ? true : false;
            }
            $providers = Provider::select('id', 'ruc', 'company_name')->get();
            return Inertia::render(
                'ProjectArea/ProjectManagement/PextExpenses',
                [
                    'expense' => $expense,
                    'providers' => $providers,
                    'cicsa_assignation_id' => $cicsa_assignation_id,
                    'fixedOrAdditional' => false
                ]
            );
        } else {
            $rejected = $request->rejected;
            $expense = PextProjectExpense::with(['provider:id,company_name', 'cicsa_assignation.project.cost_center'])
                ->where('fixedOrAdditional', true)
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
        $validatedData['is_accepted'] = json_decode($validatedData['is_accepted']);
        $validatedData['amount'] = floatval($validatedData['amount']);
        $validatedData['fixedOrAdditional'] = json_decode($validatedData['fixedOrAdditional']);
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
        $text = "Mantto";
        if ($request->isMethod('get')) {
            $project = CicsaAssignation::with('project.cost_center')
                ->whereHas('project.cost_center', function ($query) use ($text) {
                    $query->where('name', 'not like', "%$text%");
                })
                ->orderBy('created_at', 'desc')
                ->paginate();
            $cost_line = CostLine::where('name', 'Pext')->with(['cost_center' => function ($query) use ($text) {
                $query->where('name', 'not like', "%$text%");
            }])->first();

            return Inertia::render('ProjectArea/ProjectManagement/PextProjectAdditional', [
                'project' => $project,
                'cost_line' => $cost_line
            ]);
        } elseif ($request->isMethod('post')) {
            $searchQuery = $request->searchQuery;
            $project = CicsaAssignation::with('project.cost_center')
                ->whereHas('project.cost_center', function ($query) use ($text) {
                    $query->where('name', 'not like', "%$text%");
                })->where(function ($query) use ($searchQuery) {
                    $query->orWhere('project_name', 'like', "%$searchQuery%")
                        ->orWhere('project_code', 'like', "%$searchQuery%")
                        ->orWhere('cpe', 'like', "%$searchQuery%");
                })
                ->orderBy('created_at', 'desc')
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
        $cicsaAssignation = CicsaAssignation::updateOrCreate(
            ['id' => $cicsa_assignation_id],
            $validateData
        );
        $cicsaAssignation->load('project.cost_center');
        return response()->json($cicsaAssignation, 200);
    }

    public function additional_expense_index_fixed(Request $request, $cicsa_assignation_id)
    {
        if ($request->isMethod('get')) {
            $expense = PextProjectExpense::with(['provider:id,company_name', 'cicsa_assignation.project.cost_center'])
                ->where('fixedOrAdditional', false)
                ->where('cicsa_assignation_id', $cicsa_assignation_id)
                ->where('state', false)
                ->where(function ($query) {
                    $query->where('is_accepted', 1)
                        ->orWhere('is_accepted', null);
                })
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
                    'cost_center' => $cost_line->cost_center,
                    'fixedOrAdditional' => false
                ]
            );
        } else {
            $rejected = $request->rejected;
            $fixedOrAdditional = $request->fixedOrAdditional;
            $expense = PextProjectExpense::with(['provider:id,company_name', 'cicsa_assignation.project.cost_center'])
                ->where('fixedOrAdditional', false)
                ->where('cicsa_assignation_id', $cicsa_assignation_id)
                ->where('state', $fixedOrAdditional)
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

    public function additional_expense_additional_index(Request $request, $cicsa_assignation_id)
    {
        if ($request->isMethod('get')) {
            $expense = PextProjectExpense::with(['provider:id,company_name', 'cicsa_assignation.project.cost_center'])
                ->where('fixedOrAdditional', false)
                ->where('cicsa_assignation_id', $cicsa_assignation_id)
                ->where('state', true)
                ->where(function ($query) {
                    $query->where('is_accepted', 1)
                        ->orWhere('is_accepted', null);
                })
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
                    'cost_center' => $cost_line->cost_center,
                    'fixedOrAdditional' => true
                ]
            );
        } else {
            $rejected = $request->rejected;
            $fixedOrAdditional = $request->fixedOrAdditional;
            $expense = PextProjectExpense::with(['provider:id,company_name', 'cicsa_assignation.project.cost_center'])
                ->where('fixedOrAdditional', false)
                ->where('cicsa_assignation_id', $cicsa_assignation_id)
                ->where('state', $fixedOrAdditional)
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
