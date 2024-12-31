<?php

namespace App\Http\Controllers\ProjectArea;

use App\Exports\PextExpenseExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cicsa\StoreOrUpdateAssigantionRequest;
use App\Http\Requests\PextProjectRequest\StoreOrUpdateRequest;
use App\Models\AccountStatement;
use App\Models\CicsaAssignation;
use App\Models\CostLine;
use App\Models\PextProjectExpense;
use App\Models\Preproject;
use App\Models\Project;
use App\Models\ProjectQuote;
use App\Models\ProjectQuoteValuation;
use App\Models\Provider;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
            $cicsa_assignation = CicsaAssignation::with('project.cost_center', 'project.preproject')
                ->whereHas('project', function ($query) use ($text) {
                    $query->whereHas('cost_center', function ($costCenterQuery) use ($text) {
                        $costCenterQuery->where('name', 'like', "%$text%");
                    })->whereHas('preproject');
                })
                ->orderBy('created_at', 'desc')
                ->paginate();

            return Inertia::render('ProjectArea/ProjectManagement/PextProjectMonthly', [
                'cicsa_assignation' => $cicsa_assignation,
            ]);
        } elseif ($request->isMethod('post')) {
            $searchQuery = $request->searchQuery;
            $project = CicsaAssignation::with('project.cost_center')
                ->whereHas('project', function ($query) use ($text) {
                    $query->whereHas('cost_center', function ($costCenterQuery) use ($text) {
                        $costCenterQuery->where('name', 'like', "%$text%");
                    })->whereHas('preproject');
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

    public function requestProjectOrPreproject($type)
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
                ->whereHas('cost_center', function ($query) {
                    $query->where('name', 'not like', "%Mantto%");
                })
                ->whereDoesntHave('project')
                ->get();
        }
        $pro->each->setAppends([]);
        return response()->json($pro, 200);
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

        $cicsaAssignation = CicsaAssignation::updateOrCreate(
            ['id' => $cicsa_assignation_id],
            $validatedData
        );

        $cicsaAssignation->load('project.cost_center');
        return response()->json($cicsaAssignation, 200);
    }

    public function storeProjectAndAssignation(Request $request)
    {
        $validateData = $request->validate([
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
            'pre_project_id' => 'required|numeric',
        ]);
        DB::beginTransaction();
        try {
            $preproject = Preproject::find($validateData['pre_project_id']);
            $project = Project::create([
                'priority' => 'Alta',
                'description' => $validateData['project_name'],
                'cost_center_id' => $preproject->cost_center_id,
                'cost_line_id' => $preproject->cost_line_id,
                'preproject_id' => $validateData['pre_project_id']
            ]);
            $validateData['project_id'] = $project->id;
            $cicsaAssignation = CicsaAssignation::create($validateData);
            $cicsaAssignation->load('project.cost_center');
            DB::commit();
            return response()->json($cicsaAssignation, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }
    }


    // public function delete(PextProject $pext)
    // {
    //     $pext->delete();
    //     return response()->noContent();
    // }

    // public function export_expenses(Request $request)
    // {
    //     $startDate = $request->start_date;
    //     $endDate = $request->end_date;
    //     return Excel::download(new PextExpenseExport(null, $startDate, $endDate), 'Gastos_Pext.xlsx');
    // }

    public function index_expenses($project_id, $fixedOrAdditional)
    {
        $expense = PextProjectExpense::with(['provider:id,company_name', 'project.cost_center'])
            ->where('fixedOrAdditional', json_decode($fixedOrAdditional))
            ->where('project_id', $project_id)
            ->where(function ($query) {
                $query->where('is_accepted', 1)
                    ->orWhere('is_accepted', null);
            })
            ->orderBy('created_at', 'desc')
            ->paginate();
        foreach ($expense as $exp) {
            $exp->setAppends(['real_amount', 'real_state']);
            $exp->state = 1 ? true : false;
        }
        $providers = Provider::select('id', 'ruc', 'company_name')->get();
        return Inertia::render(
            'ProjectArea/ProjectManagement/PextExpenses',
            [
                'expense' => $expense,
                'providers' => $providers,
                'project_id' => $project_id,
                'fixedOrAdditional' => json_decode($fixedOrAdditional)
            ]
        );
    }

    // public function search_advance_expenses_monthly(Request $request, $project_id)
    // {
    //     $rejected = $request->rejected;
    //     $fixedOrAdditional = $request->fixedOrAdditional;
    //     $expense = PextProjectExpense::with(['provider:id,company_name', 'project.cost_center'])
    //         ->where('fixedOrAdditional', $fixedOrAdditional)
    //         ->where('project_id', $project_id)
    //         ->orderBy('created_at', 'desc');
    //     $expense = $request->state === false ? $expense->where('is_accepted', 0)
    //         : $expense->where(function ($query) use ($rejected) {
    //             $query->where('is_accepted', $rejected)
    //                 ->orWhere('is_accepted', null);
    //         });

    //     if ($rejected) {
    //         $expense->where('is_accepted', $request->rejected);
    //     }
    //     if ($request->search) {
    //         $searchTerms = $request->input('search');
    //         $expense = $expense->where(function ($query) use ($searchTerms) {
    //             $query->where('ruc', 'like', "%$searchTerms%")
    //                 ->orWhere('doc_date', 'like', "%$searchTerms%")
    //                 ->orWhere('description', 'like', "%$searchTerms%")
    //                 ->orWhere('amount', 'like', "%$searchTerms%");
    //         });
    //     }
    //     // if (count($request->selectedCostCenter) < 6) {
    //     //     $costCenter = $request->selectedCostCenter;
    //     //     $expense = $expense->whereHas('project.cost_center', function ($query) use ($costCenter) {
    //     //         $query->whereIn('name', $costCenter);
    //     //     });
    //     // }

    //     if (count($request->selectedZones) < 7) {
    //         $expense = $expense->whereIn('zone', $request->selectedZones);
    //     }
    //     if (count($request->selectedExpenseTypes) < 14) {
    //         $expense = $expense->whereIn('expense_type', $request->selectedExpenseTypes);
    //     }
    //     if (count($request->selectedDocTypes) < 5) {
    //         $expense = $expense->whereIn('type_doc', $request->selectedDocTypes);
    //     }
    //     $expense = $expense->orderBy('doc_date')->get();

    //     $expense->transform(function ($item) {
    //         $item->setAppends(['real_amount','real_state']);
    //         $item->state = 1 ? true : false;
    //         return $item;
    //     });

    //     return response()->json($expense, 200);
    // }

    public function expenses_storeOrUpdate(StoreOrUpdateRequest $request, $expense_id = null)
    {
        $validatedData = $request->validated();
        $validatedData['is_accepted'] = json_decode($validatedData['is_accepted']);
        $validatedData['amount'] = floatval($validatedData['amount']);
        $validatedData['fixedOrAdditional'] = json_decode($validatedData['fixedOrAdditional']);
        // $validatedData['state'] = json_decode($validatedData['state']);

        // $validatedData['account_statement_id'] = null;
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

        $expense->load('project.cost_center');
        $expense->setAppends(['real_amount', 'real_state']);
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

    public function expense_export($project_id, $fixedOrAdditional)
    {
        $fOrA = json_decode($fixedOrAdditional) ? 'Fijos' : 'Adicionales';
        $project_name = CicsaAssignation::select('id', 'project_name')->where('project_id', $project_id)->first();
        return Excel::download(new PextExpenseExport($project_id, json_decode($fixedOrAdditional)), 'Gastos_Pext' . '-' . $project_name->project_name . '-' . $fOrA . '.xlsx');
    }

    public function index_additional(Request $request, $type)
    {
        $text = "Mantto";
        if ($request->isMethod('get')) {
            $project = CicsaAssignation::with('project.cost_center', 'project.project_quote.project_quote_valuations')
                ->whereHas('project.cost_center', function ($query) use ($text, $type) {
                    $query->where('name', 'not like', "%$text%")->where('cost_line_id', $type);
                })
                ->whereDoesntHave('project.preproject')
                ->orderBy('created_at', 'desc')
                ->paginate();

            $cost_line = CostLine::where('name', 'Pext')->with(['cost_center' => function ($query) use ($text) {
                $query->where('name', 'not like', "%$text%");
            }])->first();

            return Inertia::render('ProjectArea/ProjectManagement/ProjectAdditional', [
                'project' => $project,
                'cost_line' => $cost_line,
                'type' => $type,
            ]);
        } elseif ($request->isMethod('post')) {
            $searchQuery = $request->searchQuery;
            $project = CicsaAssignation::with('project.cost_center', 'project.project_quote.project_quote_valuations')
                ->whereHas('project.cost_center', function ($query) use ($text) {
                    $query->where('name', 'not like', "%$text%");
                })->where(function ($query) use ($searchQuery) {
                    $query->orWhere('project_name', 'like', "%$searchQuery%")
                        ->orWhere('project_code', 'like', "%$searchQuery%")
                        ->orWhere('cpe', 'like', "%$searchQuery%");
                })
                ->whereDoesntHave('project.preproject')
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

    public function store_quote(Request $request, $project_quote_id = null)
    {
        $validateData = $request->validate([
            'project_id' => 'required|numeric',
            'delivery_place' => 'required|string',
            'delivery_time' => 'required|numeric',
            'observations' => 'required|string',
            'project_quote_valuations' => 'required|array',
        ]);
        DB::beginTransaction();
        try {
            $project_quote = ProjectQuote::updateOrCreate(
                ['id' => $project_quote_id],
                $validateData
            );

            $valuations = collect($validateData['project_quote_valuations'])->map(function ($item) use ($project_quote) {
                $item['project_quote_id'] = $project_quote->id;
                return $item;
            });

            $receivedIds = $valuations->pluck('id')->filter()->toArray();

            ProjectQuoteValuation::where('project_quote_id', $project_quote->id)
                ->whereNotIn('id', $receivedIds)
                ->delete();

            foreach ($valuations as $valuation) {
                ProjectQuoteValuation::updateOrCreate(
                    ['id' => $valuation['id'] ?? null],
                    $valuation
                );
            }
            $project_quote->load('project_quote_valuations');
            DB::commit();
            return response()->json($project_quote, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }
    }

    public function export_quote($project_id)
    {
        $project = Project::with('project_quote.project_quote_valuations', 'cicsa_assignation')
            ->find($project_id);
        $pdf = Pdf::loadView('pdf.CotizationPDFProject', compact('project'));
        return $pdf->stream();
    }

    public function additional_expense_index($project_id, $fixedOrAdditional, $type)
    {
        $expense = PextProjectExpense::with(['provider:id,company_name', 'project.cost_center'])
            ->where('fixedOrAdditional', json_decode($fixedOrAdditional))
            ->where('project_id', $project_id)
            ->where(function ($query) {
                $query->where('is_accepted', 1)
                    ->orWhere('is_accepted', null);
            })
            ->orderBy('created_at', 'desc')
            ->paginate();
        foreach ($expense as $exp) {
            $exp->setAppends(['real_amount', 'real_state']);
        }
        $providers = Provider::select('id', 'ruc', 'company_name')->get();
        $cost_line = CostLine::where('name', 'PEXT')->with('cost_center')->first();
        $cicsa_assignation = CicsaAssignation::select('id', 'project_id', 'zone')
            ->where('project_id', $project_id)
            ->first();
        return Inertia::render(
            'ProjectArea/ProjectManagement/ProjectAdditionalExpenses',
            [
                'expense' => $expense,
                'providers' => $providers,
                'project_id' => $project_id,
                'cost_center' => $cost_line->cost_center,
                'fixedOrAdditional' => json_decode($fixedOrAdditional),
                'cicsaAssignation' => $cicsa_assignation,
                'type' => $type
            ]
        );
    }

    public function search_advance_monthly_or_additional_expense(Request $request, $project_id)
    {
        $rejected = $request->rejected;
        $fixedOrAdditional = ($request->fixedOrAdditional);
        $expense = PextProjectExpense::with(['provider:id,company_name', 'project.cost_center'])
            ->where('fixedOrAdditional', $fixedOrAdditional)
            ->where('project_id', $project_id)
            ->orderBy('created_at', 'desc');
        $expense = !$rejected ? $expense->where('is_accepted', 0)
            : $expense->where(function ($query) {
                $query->where('is_accepted', 1)
                    ->orWhere('is_accepted', null);
            });
            
        if ($request->search) {
            $searchTerms = $request->input('search');
            $expense = $expense->where(function ($query) use ($searchTerms) {
                $query->where('ruc', 'like', "%$searchTerms%")
                    ->orWhere('doc_date', 'like', "%$searchTerms%")
                    ->orWhere('description', 'like', "%$searchTerms%")
                    ->orWhere('amount', 'like', "%$searchTerms%");
            });
        }

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

        $expense->transform(function ($item) {
            $item->setAppends(['real_amount', 'real_state']);
            return $item;
        });

        if (count($request->selectedStateTypes)) {
            $expense = $expense->filter(function ($item) use ($request) {
                return in_array($item->real_state, $request->selectedStateTypes);
            })->values()->all();
        }

        return response()->json($expense, 200);
    }

    public function masiveUpdate(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
            'operation_date' => 'required|date',
            'operation_number' => 'required|min:6',
        ]);
        $on = substr($data['operation_number'], -6);
        $as = AccountStatement::where('operation_date', $data['operation_date'])
            ->where('operation_number', $on)
            ->first();

        $data['account_statement_id'] = $as?->id;
        $costs = PextProjectExpense::whereIn('id', $data['ids'])->get();
        foreach ($costs as $cost) {
            $cost->update([
                'operation_date' => $data['operation_date'],
                'operation_number' => $data['operation_number'],
                'account_statement_id' => $data['account_statement_id'],
                'is_accepted' => 1,
            ]);
        }
        $updatedCosts = PextProjectExpense::whereIn('id', $data['ids'])
            ->with(['project', 'provider:id,company_name'])
            ->get();
        $updatedCosts->each(function ($cost) {
            $cost->project->setAppends([]);
            $cost->setAppends(['real_amount', 'real_state']);
        });
        return response()->json($updatedCosts, 200);
    }
}
