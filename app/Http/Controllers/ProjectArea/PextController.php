<?php

namespace App\Http\Controllers\ProjectArea;

use App\Exports\PextExpenseExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\PextProjectRequest\StoreOrUpdateAssignationRequest;
use App\Http\Requests\PextProjectRequest\StoreOrUpdateExpenseRequest;
use App\Models\AccountStatement;
use App\Models\CicsaAssignation;
use App\Models\CostLine;
use App\Models\PextProjectExpense;
use App\Models\Preproject;
use App\Models\Project;
use App\Models\ProjectQuote;
use App\Models\ProjectQuoteValuation;
use App\Models\Provider;
use App\Services\PextProjectServices;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class PextController extends Controller
{
    protected $pextServices;

    public function __construct(PextProjectServices $services)
    {
        $this->pextServices = $services;
    }

    public function index(Request $request)
    {
        if ($request->isMethod('get')) {
            $cicsa_assignation = $this->pextServices->getCicsaAssignation();
            return Inertia::render('ProjectArea/ProjectManagement/PextProjectMonthly', [
                'cicsa_assignation' => $cicsa_assignation,
            ]);
        } elseif ($request->isMethod('post')) {
            $searchQuery = $request->searchQuery;
            $project = $this->pextServices->searchCicsaAssignation($searchQuery);
            return response()->json($project, 200);
        }
    }

    public function search(Request $request)
    {
        $searchQuery = $request->searchQuery;
        $project = $this->pextServices->searchCicsaAssignation($searchQuery);
        return response()->json($project, 200);
    }

    public function requestProjectOrPreproject($type)
    {
        $pro = $this->pextServices->getProjectOrProject($type);
        return response()->json($pro, 200);
    }

    public function storeOrUpdate(StoreOrUpdateAssignationRequest $request, $cicsa_assignation_id = null)
    {
        $validatedData = $request->validated();
        $cicsaAssignation = $this->pextServices->storeOrUpdateAssignation($validatedData, $cicsa_assignation_id);
        return response()->json($cicsaAssignation, 200);
    }

    public function storeProjectAndAssignation(StoreOrUpdateAssignationRequest $request)
    {
        $validateData = $request->validated();
        DB::beginTransaction();
        try {
            $validateData = $this->pextServices->storeProject($validateData);
            $cicsaAssignation = $this->pextServices->storeOrUpdateAssignation($validateData, null);
            DB::commit();
            return response()->json($cicsaAssignation, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }
    }

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

    public function expenses_storeOrUpdate(StoreOrUpdateExpenseRequest $request, $expense_id = null)
    {
        $validatedData = $request->validated();
        $validatedData['is_accepted'] = json_decode($validatedData['is_accepted']);
        $validatedData['amount'] = floatval($validatedData['amount']);
        $validatedData['fixedOrAdditional'] = json_decode($validatedData['fixedOrAdditional']);

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
        $project_name = CicsaAssignation::select('id', 'project_name')
            ->where('project_id', $project_id)
            ->first();
        $fileName = 'Gastos_Pext' . '-' . $project_name->project_name . '-' . $fOrA . '.xlsx';
        return Excel::download(new PextExpenseExport($project_id, json_decode($fixedOrAdditional)), $fileName);
    }

    public function expense_export_general($fixedOrAdditional)
    {
        $fOrA = json_decode($fixedOrAdditional) ? 'Fijos' : 'Adicionales';
        $fileName = 'Gastos_Pext' . '-' . $fOrA . '.xlsx';
        return Excel::download(new PextExpenseExport(null, json_decode($fixedOrAdditional)), $fileName);
    }

    public function index_additional(Request $request, $type, $searchCondition = null,)
    {
        $text = "Mantto";
        if ($request->isMethod('get')) {
            $project = null;
            if ($type == 2) {
                $project = CicsaAssignation::with('project.cost_center', 'project.project_quote.project_quote_valuations')
                    ->whereHas('project', function ($query) {
                        $query->where('is_accepted', 1);
                    })
                    ->whereHas('project.cost_center', function ($query) use ($text) {
                        $query->where('name', 'not like', "%$text%")->where('cost_line_id', 2);
                    })
                    ->whereDoesntHave('project.preproject')
                    ->orderBy('created_at', 'desc')
                    ->paginate();
            }

            if ($type == 1) {
                $project = CicsaAssignation::with('project.cost_center',  'project.project_quote.project_quote_valuations')
                    ->whereHas('project', function ($query) {
                        $query->where('cost_center_id', 3)->where('is_accepted', 1);
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate();
            }

            $cost_line = null;

            if ($type == 2) {
                $cost_line = CostLine::where('name', 'Pext')->with(['cost_center' => function ($query) use ($text) {
                    $query->where('name', 'not like', "%$text%");
                }])->first();
            }
            if ($type == 1) {
                $cost_line = CostLine::where('id', '1')->with(['cost_center' => function ($query) {
                    $query->where('id', 3);
                }])->first();
            }

            return Inertia::render('ProjectArea/ProjectManagement/ProjectAdditional', [
                'project' => $project,
                'cost_line' => $cost_line,
                'searchCondition' => $searchCondition,
                'type' => $type,
            ]);
        } elseif ($request->isMethod('post')) {
            $searchQuery = $request->searchQuery;
            $project = null;
            if ($type == 2) {
                $project = CicsaAssignation::with('project.cost_center', 'project.project_quote.project_quote_valuations')
                    ->whereHas('project', function ($query) {
                        $query->where('is_accepted', 1);
                    })
                    ->whereHas('project.cost_center', function ($query) use ($text) {
                        $query->where('name', 'not like', "%$text%");
                    })->whereDoesntHave('project.preproject');
            }
            if ($type == 1) {
                $project = CicsaAssignation::with('project.cost_center', 'project.project_quote.project_quote_valuations')
                    ->whereHas('project.cost_center', function ($query) {
                        $query->where('id', 3)->where('is_accepted', 1);
                    });
            }

            $project = $project->where(function ($query) use ($searchQuery) {
                $query->orWhere('project_name', 'like', "%$searchQuery%")
                    ->orWhere('project_code', 'like', "%$searchQuery%")
                    ->orWhere('cpe', 'like', "%$searchQuery%");
            })

                ->orderBy('created_at', 'desc')
                ->get();
            return response()->json($project, 200);
        }
    }


    public function index_additional_rejected(Request $request, $type)
    {
        $text = "Mantto";
        if ($request->isMethod('get')) {
            $project = null;
            if ($type == 2) {
                $project = CicsaAssignation::with('project.cost_center', 'project.project_quote.project_quote_valuations')
                    ->whereHas('project', function ($query) {
                        $query->where('is_accepted', 0);
                    })
                    ->whereHas('project.cost_center', function ($query) use ($text) {
                        $query->where('name', 'not like', "%$text%")->where('cost_line_id', 2);
                    })
                    ->whereDoesntHave('project.preproject')
                    ->orderBy('created_at', 'desc')
                    ->paginate();
            }

            if ($type == 1) {
                $project = CicsaAssignation::with('project.cost_center', 'project.project_quote.project_quote_valuations')
                    ->whereHas('project', function ($query) {
                        $query->where('cost_center_id', 3)->where('is_accepted', 0);
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate();
            }

            $cost_line = null;

            if ($type == 2) {
                $cost_line = CostLine::where('name', 'Pext')->with(['cost_center' => function ($query) use ($text) {
                    $query->where('name', 'not like', "%$text%");
                }])->first();
            }
            if ($type == 1) {
                $cost_line = CostLine::where('id', '1')->with(['cost_center' => function ($query) {
                    $query->where('id', 3);
                }])->first();
            }

            return Inertia::render('ProjectArea/ProjectManagement/ProjectAdditionalRejected', [
                'project' => $project,
                'cost_line' => $cost_line,
                'type' => $type,
            ]);
        } elseif ($request->isMethod('post')) {
            $searchQuery = $request->searchQuery;
            $project = null;
            if ($type == 2) {
                $project = CicsaAssignation::with('project.cost_center', 'project.project_quote.project_quote_valuations')
                    ->whereHas('project', function ($query) {
                        $query->where('is_accepted', 0);
                    })
                    ->whereHas('project.cost_center', function ($query) use ($text) {
                        $query->where('name', 'not like', "%$text%");
                    })->whereDoesntHave('project.preproject');
            }
            if ($type == 1) {
                $project = CicsaAssignation::with('project.cost_center', 'project.project_quote.project_quote_valuations')
                    ->whereHas('project.cost_center', function ($query) {
                        $query->where('id', 3)->where('is_accepted', 0);
                    });
            }

            $project = $project->where(function ($query) use ($searchQuery) {
                $query->orWhere('project_name', 'like', "%$searchQuery%")
                    ->orWhere('project_code', 'like', "%$searchQuery%")
                    ->orWhere('cpe', 'like', "%$searchQuery%");
            })

                ->orderBy('created_at', 'desc')
                ->get();
            return response()->json($project, 200);
        }
    }


    public function rejectProjectAdditional($pa_id)
    {
        $rg = Project::find($pa_id);
        $rg->update(['is_accepted' => 0]);
        return response()->json(['msg' => true]);
    }

    public function updateOrStoreAdditional(StoreOrUpdateAssignationRequest $request, $cicsa_assignation_id = null)
    {
        $validateData = $request->validated();
        if ($cicsa_assignation_id == null) {
            $project = Project::create([
                'priority' => 'Alta',
                'description' => $validateData['project_name'],
                'cost_line_id' => $validateData['cost_line_id'],
                'cost_center_id' => $validateData['cost_center_id']
            ]);
            $validateData['project_id'] = $project->id;
        }

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
            'fee' => 'required|boolean',
            'user_id' => 'required',
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
        if ($project_id) {
            $project = Project::with('project_quote.user', 'project_quote.project_quote_valuations', 'cicsa_assignation')
                ->find($project_id);
        }
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


        $project = Project::find($project_id);
        $acArr = $project
            ->pext_project_expenses()
            ->where('fixedOrAdditional', 0)
            ->where(function ($query) {
                $query->where('is_accepted', 1)
                    ->orWhere('is_accepted', null);
            })
            ->select('expense_type', DB::raw('SUM(amount/(1+igv/100)) as total_amount'))
            ->groupBy('expense_type')
            ->get();
        $acExpensesAmounts = $acArr->map(function ($cost) {
            return [
                'expense_type' => $cost->expense_type,
                'total_amount' => $cost->total_amount,
            ];
        })->toArray();

        $scArr = $project
            ->pext_project_expenses()
            ->where('fixedOrAdditional', 1)
            ->where(function ($query) {
                $query->where('is_accepted', 1)
                    ->orWhere('is_accepted', null);
            })
            ->select('expense_type', DB::raw('SUM(amount/(1+igv/100)) as total_amount'))
            ->groupBy('expense_type')
            ->get();
        $scExpensesAmounts = $scArr->map(function ($cost) {
            return [
                'expense_type' => $cost->expense_type,
                'total_amount' => $cost->total_amount,
            ];
        })->toArray();

        return Inertia::render(
            'ProjectArea/ProjectManagement/ProjectAdditionalExpenses',
            [
                'expense' => $expense,
                'providers' => $providers,
                'project_id' => $project_id,
                'cost_center' => $cost_line->cost_center,
                'fixedOrAdditional' => json_decode($fixedOrAdditional),
                'cicsaAssignation' => $cicsa_assignation,
                'type' => $type,
                'acExpensesAmounts' => $acExpensesAmounts,
                'scExpensesAmounts' => $scExpensesAmounts,
            ]
        );
    }

    public function additional_expense_index_general($fixedOrAdditional, $type)
    {
        $prevExpense = PextProjectExpense::with([
            'provider:id,company_name',
            'project.cost_center',
        ])
            ->where('fixedOrAdditional', json_decode($fixedOrAdditional))
            ->whereHas('project', function ($query) use ($type) {
                $query->where('cost_line_id', $type);
            })
            ->where(function ($query) {
                $query->where('is_accepted', 1)
                    ->orWhere('is_accepted', null);
            });
        $expense = $prevExpense
            ->orderBy('created_at', 'desc')
            ->paginate();
        foreach ($expense as $exp) {
            $exp->setAppends(['real_amount', 'real_state']);
        }

        $acArr = $prevExpense
            ->where('fixedOrAdditional', 0)
            ->select('expense_type', DB::raw('SUM(amount/(1+igv/100)) as total_amount'))
            ->groupBy('expense_type')
            ->get();
        $acExpensesAmounts = $acArr->map(function ($cost) {
            return [
                'expense_type' => $cost->expense_type,
                'total_amount' => $cost->total_amount,
            ];
        })->toArray();

        $scArr = $prevExpense
            ->where('fixedOrAdditional', 1)
            ->select('expense_type', DB::raw('SUM(amount/(1+igv/100)) as total_amount'))
            ->groupBy('expense_type')
            ->get();
        $scExpensesAmounts = $scArr->map(function ($cost) {
            return [
                'expense_type' => $cost->expense_type,
                'total_amount' => $cost->total_amount,
            ];
        })->toArray();


        $providers = Provider::select('id', 'ruc', 'company_name')->get();

        $cost_line = null;
        if ($type == 2) {
            $cost_line = CostLine::where('name', 'PEXT')->with('cost_center')->first();
        }
        if ($type == 1) {
            $cost_line = CostLine::where('name', 'PINT')->with('cost_center')->first();
        }

        // $cicsa_assignation = null;
        // if($type == 2){$cicsa_assignation = CicsaAssignation::select('id', 'project_name', 'project_id', 'zone')->get();}
        // if($type == 1){
        //     $cicsa_assignation = CicsaAssignation::select('id', 'project_name', 'project_id', 'zone')
        //         ->whereHas('project', function($query){
        //             $query->where('cost_center_id', 3);
        //         })
        //         ->get();
        // }

        return Inertia::render(
            'ProjectArea/ProjectManagement/ProjectAdditionalExpensesGeneral',
            [
                'expense' => $expense,
                'providers' => $providers,
                'cost_center' => $cost_line->cost_center,
                'fixedOrAdditional' => json_decode($fixedOrAdditional),
                'cicsaAssignation' => $cicsa_assignation,
                'type' => $type,
                'acExpensesAmounts' => $acExpensesAmounts,
                'scExpensesAmounts' => $scExpensesAmounts,
            ]
        );
    }

    public function getCicsaAssignation(Request $request)
    {
        $type = $request->type;
        $zone = $request->zone;
        $cicsa_assignation = null;

        if ($type == 2) {
            $cicsa_assignation = CicsaAssignation::select('id', 'project_name', 'project_id', 'zone')
                ->when($zone, function ($query, $zone) {
                    return $query->where('zone', $zone);
                })
                ->get();
        }

        if ($type == 1) {
            $cicsa_assignation = CicsaAssignation::select('id', 'project_name', 'project_id', 'zone')
                ->whereHas('project', function ($query) {
                    $query->where('cost_center_id', 3);
                })
                ->when($zone, function ($query, $zone) {
                    return $query->where('zone', $zone);
                })
                ->get();
        }

        return response()->json($cicsa_assignation, 200);
    }

    public function search_advance_monthly_or_additional_expense(Request $request, $project_id)
    {
        $rejected = $request->rejected;
        $fixedOrAdditional = $request->fixedOrAdditional;
        $searchTerms = $request->search;
        $selectedStateTypes = $request->selectedStateTypes;
        $expense = $this->pextServices->baseSearch($fixedOrAdditional);
        $expense = $this->pextServices->differentialSearch($project_id, $expense);
        $expense = $this->pextServices->rejectedSearch($request, $rejected);
        $expense = $this->pextServices->textSearch($request, $searchTerms);
        $expense = $this->pextServices->filterAdvance($request, $expense);
        $expense = $this->pextServices->addCalculatedFields($expense);
        $expense = $this->pextServices->filterCalculatedFields($request, $selectedStateTypes);

        return response()->json($expense, 200);
    }

    public function search_advance_additional_expense_general(Request $request)
    {
        $rejected = $request->rejected;
        $fixedOrAdditional = $request->fixedOrAdditional;
        $searchTerms = $request->search;
        $selectedStateTypes = $request->selectedStateTypes;
        $expense = $this->pextServices->baseSearch($fixedOrAdditional);
        $expense = $this->pextServices->rejectedSearch($expense, $rejected);
        $expense = $this->pextServices->textSearch($expense, $searchTerms);
        $expense = $this->pextServices->filterAdvance($expense, $request);
        $expense = $this->pextServices->addCalculatedFields($expense);
        $expense = $this->pextServices->filterCalculatedFields($expense, $selectedStateTypes);
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
