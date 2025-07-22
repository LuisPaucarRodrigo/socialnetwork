<?php

namespace App\Http\Controllers\ProjectArea\Pext;

use App\Constants\PextConstants;
use App\Exports\PextExpenseExport;
use App\Exports\Project\Expenses\PextExpensesStructureExport;
use App\Helpers\FileHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\PextProjectRequest\StoreOrUpdateExpenseRequest;
use App\Http\Requests\PextProjectRequest\SwapAdditionalToAdditionalRequest;
use App\Imports\PextProjectExpensesImport;
use App\Models\AccountStatement;
use App\Models\CicsaAssignation;
use App\Models\CostLine;
use App\Models\PextProjectExpense;
use App\Models\Project;
use App\Models\Provider;
use App\Services\PextProjectServices;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;

class PextExpensesController extends Controller
{
    protected $pextServices;

    public function __construct(PextProjectServices $services)
    {
        $this->pextServices = $services;
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
            $validatedData['photo'] = $validatedData['doc_number'] . '--' . time();
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
        return response()->json([], 200);
    }

    public function expense_show_image(PextProjectExpense $expense_id)
    {
        $fileName = $expense_id->photo;
        return FileHandler::showFile('/documents/expensesPext/', $fileName);
    }

    public function expense_validate(Request $request, $expense_id)
    {
        $validatedData = $request->validate([
            'is_accepted' => 'required|boolean',
            'operation_date' => 'sometimes|required',
            'operation_number' => 'sometimes|required',
        ]);
        $expense = PextProjectExpense::find($expense_id);
        $expense->update($validatedData);
        $expense->append('real_state');

        return response()->json($expense, 200);
    }

    public function downloadImages(Request $request, $project_id, $fixedOrAdditional)
    {
        try {
            $query = PextProjectExpense::where('project_id', $project_id)
                ->where('fixedOrAdditional', json_decode($fixedOrAdditional))
                ->where('is_accepted', 1);

            $expenses = $this->pextServices->filterAdvance($query, $request)->get();

            $expensesWithPhotos = $expenses->filter(function ($item) {
                return !empty($item->photo) && file_exists(public_path("/documents/expensesPext/{$item->photo}"));
            });

            if ($expensesWithPhotos->isEmpty()) {
                return response()->json([
                    'error' => 'No hay imágenes disponibles para descargar.'
                ], 404);
            }
            $zipFileName = 'expensesPhotos.zip';
            $zipFilePath = public_path("/documents/expensesPext/{$zipFileName}");
            $zip = new ZipArchive;
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                foreach ($expenses as $cost) {
                    if (!empty($cost->photo)) {
                        $photoPath = public_path("/documents/expensesPext/{$cost->photo}");
                        if (file_exists($photoPath)) {
                            $zip->addFile($photoPath, $cost->photo);
                        }
                    }
                }
                $zip->close();
                ob_end_clean();
                return response()->download($zipFilePath)->deleteFileAfterSend(true);
            } else {
                return response()->json(['error' => 'No se pudo abrir el archivo ZIP para escritura.'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo crear el archivo ZIP.'], 500);
        }
    }

    public function expense_export($project_id, $fixedOrAdditional)
    {
        $fOrA = json_decode($fixedOrAdditional) ? 'Fijos' : 'Adicionales';
        $project_name = CicsaAssignation::select('id', 'project_name')
            ->where('project_id', $project_id)
            ->first();
        $fileName = 'Gastos_Pext' . '-' . $project_name->project_name . '-' . $fOrA . '.xlsx';
        return Excel::download(new PextExpenseExport(null, $project_id, json_decode($fixedOrAdditional)), $fileName);
    }

    public function expense_export_general($fixedOrAdditional, $cost_line)
    {
        $fOrA = json_decode($fixedOrAdditional) ? 'Fijos' : 'Adicionales';
        $fileName = 'Gastos_Pext' . '-' . $fOrA . '.xlsx';
        return Excel::download(new PextExpenseExport($cost_line, null, json_decode($fixedOrAdditional)), $fileName);
    }

    public function structure_excel_export()
    {
        return Excel::download(new PextExpensesStructureExport, 'estructura_gastos_pext.xlsx');
    }

    public function additional_expense_index($project_id, $fixedOrAdditional, $type)
    {
        $expense = $this->pextServices->project_expenses_base($project_id, $fixedOrAdditional)->paginate();
        $expense = $this->pextServices->add_calculated_field($expense);
        $providers = Provider::select('id', 'ruc', 'company_name')->get();
        $cost_line = CostLine::where('name', 'PEXT')->with('cost_center')->first();

        $acArr = $this->pextServices->expense_calculation($project_id, 0)->get();

        $scArr = $this->pextServices->expense_calculation($project_id, 1)->get();

        $cost_center_id = $type == 1 ? 3 : 9;
        $additionalProjects = Project::whereNot('id', $project_id)->where('cost_line_id', $type)->where('cost_center_id', $cost_center_id)->select('id', 'description')->where('is_accepted', true)->orderBy('description')->get();

        return Inertia::render(
            'ProjectArea/ProjectManagement/Pext/Additional/Expenses/ProjectAdditionalExpenses',
            [
                'expense' => $expense,
                'providers' => $providers,
                'project_id' => $project_id,
                'cost_center' => $cost_line->cost_center,
                'fixedOrAdditional' => json_decode($fixedOrAdditional),
                'type' => $type,

                'zones' => PextConstants::getZone(),
                'expenseType' => PextConstants::getExpenseType(),
                'expenseTypeFixed' => PextConstants::getExpenseTypeFixed(),
                'documentsType' => PextConstants::getDocumentsType(),
                'additional_projects' => $additionalProjects,
            ]
        );
    }

    public function additional_project_expensese($project_id)
    {
        $acArr = $this->pextServices->expense_calculation($project_id, 0)->get();
        $scArr = $this->pextServices->expense_calculation($project_id, 1)->get();
        $acExpensesAmounts = $this->pextServices->map_expenses($acArr);
        $scExpensesAmounts = $this->pextServices->map_expenses($scArr);
        return response()->json([
            'acExpensesAmounts' => $acExpensesAmounts,
            'scExpensesAmounts' => $scExpensesAmounts,
        ]);
    }

    public function swap_additionalToAdditional(SwapAdditionalToAdditionalRequest $request)
    {
        $data = $request->validated();
        if ($data['type_project'] === 'same_project') {
            $first = PextProjectExpense::find($data['ids'][0]);
            $project_id = $first->project_id;
            $fixedOrAdditional = !$first->fixedOrAdditional;
        }
        if ($data['type_project'] === 'different_project') {
            $project_id = $data['project_id'];
            $fixedOrAdditional = (bool)(int)$data['type_expense'];
        }
        foreach ($data['ids'] as $id) {
            $ppe = PextProjectExpense::find($id);
            $newData = collect($ppe->toArray())
                ->except(['id', 'project_id'])
                ->toArray();
            $newData['project_id'] = $project_id;
            $newData['fixedOrAdditional'] = $fixedOrAdditional;
            $ppe->update($newData);
        }
        return response()->json(true, 200);
    }

    public function additional_expense_index_general($fixedOrAdditional, $type)
    {
        $providers = Provider::select('id', 'ruc', 'company_name')->get();
        $cost_line = $this->pextServices->getCostLine($type);
        return Inertia::render(
            'ProjectArea/ProjectManagement/GeneralExpenses/ProjectAdditionalExpensesGeneral',
            [
                'providers' => $providers,
                'cost_center' => $cost_line->cost_center,
                'fixedOrAdditional' => json_decode($fixedOrAdditional),
                'type' => $type,
                'zones' => PextConstants::getZone(),
                'expenseType' => PextConstants::getExpenseType(),
                'expenseTypeFixed' => PextConstants::getExpenseTypeFixed(),
                'documentsType' => PextConstants::getDocumentsType()
            ]
        );
    }

    public function getExpenses($fixedOrAdditional, $type)
    {
        $prevExpense = PextProjectExpense::with([
            'provider:id,company_name'
        ])
            ->where('fixedOrAdditional', json_decode($fixedOrAdditional))
            ->whereHas('project', function ($query) use ($type) {
                $query->where('cost_line_id', $type)
                    ->where("id", "!=", 320)
                    ->whereDoesntHave('preproject');
            })
            ->where(function ($query) {
                $query->where('is_accepted', 1)
                    ->orWhere('is_accepted', null);
            });

        $expense = $prevExpense
            ->orderBy('created_at', 'desc')
            ->paginate();

        $expense = $this->pextServices->add_calculated_field($expense);
        return response()->json($expense, 200);
    }

    public function getCicsaAssignation(Request $request)
    {
        $type = $request->type;
        $zone = $request->zone;
        $cicsa_assignation = null;

        if ($type == 2) {
            $cicsa_assignation = CicsaAssignation::select('id', 'project_name', 'project_id', 'zone')
                ->where(function ($z) use ($zone) {
                    $z->where('zone', $zone)
                        ->orWhere('zone2', $zone)
                        ->orWhere('zone3', $zone);
                })
                ->whereHas('project', function ($query) {
                    $query->where('cost_line_id', 2)
                        ->where('is_accepted', 1)
                        ->whereIn('cost_center_id', [6, 7, 8, 9]);
                })
                ->where(function ($query) {
                    $query->whereDoesntHave('cicsa_charge_area')
                        ->orWhereHas('cicsa_charge_area', function ($subQuery) {
                            $subQuery->where(function ($q) {
                                $q->whereNull('invoice_number')
                                    ->orWhereNull('invoice_date')
                                    ->orWhereNull('amount')
                                    ->orWhereNull('credit_to')
                                    ->orWhereNull('deposit_date')
                                    ->orWhereNull('transaction_number_current')
                                    ->orWhereNull('checking_account_amount')
                                    ->orWhere(function ($q2) {
                                        $q2->where('state_detraction', '!=', 0)
                                            ->where(function ($q3) {
                                                $q3->whereNull('deposit_date_bank')
                                                    ->orWhereNull('transaction_number_bank')
                                                    ->orWhereNull('amount_bank')
                                                    ->orWhereNull('document');
                                            });
                                    });
                            });
                        });
                })
                ->orderBy('project_name')
                ->get();
        }

        if ($type == 1) {
            $cicsa_assignation = CicsaAssignation::select('id', 'project_name', 'project_id', 'zone')
                ->whereHas('project', function ($query) {
                    $query->whereIn('cost_center_id', [3])
                        ->where('is_accepted', true);
                })
                ->where(function ($Z) use ($zone) {
                    $Z->where('zone', $zone)
                        ->orWhere('zone2', $zone)
                        ->orWhere('zone3', $zone);
                })
                ->orderBy('project_name')
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
        $expense = $this->pextServices->differentialSearch($expense, $project_id);
        $expense = $this->pextServices->rejectedSearch($expense, $rejected);
        $expense = $this->pextServices->textSearch($expense, $searchTerms);
        $expense = $this->pextServices->filterAdvance($expense, $request)->get();
        $expense = $this->pextServices->addCalculatedFields($expense);
        $expense = $this->pextServices->filterCalculatedFields($expense, $selectedStateTypes);
        return response()->json($expense, 200);
    }

    public function search_advance_additional_expense_general(Request $request)
    {
        $type = $request->type;
        $rejected = $request->rejected;
        $fixedOrAdditional = $request->fixedOrAdditional;
        $searchTerms = $request->search;
        $selectedStateTypes = $request->selectedStateTypes;
        $expense = $this->pextServices->baseSearch($fixedOrAdditional);
        $expense = $this->pextServices->differentialSearchMonthly($expense, $type);
        $expense = $this->pextServices->rejectedSearch($expense, $rejected);
        $expense = $this->pextServices->textSearch($expense, $searchTerms);
        $expense = $this->pextServices->filterAdvance($expense, $request)->get();
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

    public function masiveUpdateSwap(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required | array | min:1',
        ]);
        foreach ($data['ids'] as $id) {
            $pex = PextProjectExpense::find($id);
            $pex->update([
                'fixedOrAdditional' => $pex->fixedOrAdditional == 1 ? 0 : 1
            ]);
        }

        return response()->json(true, 200);
    }

    public function expense_dashboard($project_id)
    {

        $additionals = $this->pextServices->expenseBars($project_id, 0)->get();
        $fixed = $this->pextServices->expenseBars($project_id, 1)->get();
        $totalsAdditionals = collect(PextConstants::getExpenseType())->map(function ($type) use ($additionals) {
            $existing = $additionals->firstWhere('expense_type', $type);
            return [
                'expense_type' => $type,
                'total_amount' => $existing ? $existing->total_amount : 0,
            ];
        });

        $totalsFixed = collect(PextConstants::getExpenseTypeFixed())->map(function ($type) use ($fixed) {
            $existing = $fixed->firstWhere('expense_type', $type);
            return [
                'expense_type' => $type,
                'total_amount' => $existing ? $existing->total_amount : 0,
            ];
        });

        $expenses = [
            'fixed' => $totalsFixed,
            'additional' => $totalsAdditionals
        ];
        return Inertia::render('ProjectArea/ProjectManagement/DashboardExpensesPext', [
            'expenses' => $expenses,
            'project_id' => $project_id
        ]);
    }

    public function expense_type_zone(Request $request)
    {
        $zones = PextProjectExpense::where('project_id', $request->project_id)
            ->where('fixedOrAdditional', $request->fixedOrAdditional)
            ->where('expense_type', $request->expense_type)
            ->select('zone', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('zone')->get();

        $totalsZones = collect(PextConstants::getZone())->map(function ($zone) use ($zones) {
            $existing = $zones->firstWhere('zone', $zone);
            return [
                'zone' => $zone,
                'amount' => $existing ? $existing->total_amount : 0,
            ];
        });
        return response()->json($totalsZones, 200);
    }

    public function barChart($project_id)
    {
        // Obtener el proyecto actual
        $currentProject = Project::findOrFail($project_id);

        $currentProjects = Project::whereYear('created_at', $currentProject->created_at->year)
            ->whereMonth('created_at', $currentProject->created_at->month)
            ->whereHas('preproject')
            ->where('cost_line_id', 2)
            ->pluck('id') // Esto solo devolverá los IDs
            ->toArray(); // Convierte la colección en un array si lo necesitas como tal

        // Obtener el proyecto anterior (del mes anterior)
        $previousProject = Project::whereYear('created_at', $currentProject->created_at->year)
            ->whereMonth('created_at', $currentProject->created_at->month - 1)
            ->whereHas('preproject')
            ->where('cost_line_id', 2)
            ->pluck('id') // Esto solo devolverá los IDs
            ->toArray(); // Convierte la colección en un array si lo necesitas como tal

        // Obtener todos los proyectos del mismo año
        $annualProjects = Project::whereYear('created_at', $currentProject->created_at->year)
            ->whereHas('preproject')
            ->where('cost_line_id', 2)
            ->pluck('id')
            ->toArray();

        // Función para calcular gastos agrupados por zona
        $getExpensesByZone = function ($projectIds, $fixedOrAdditional) {
            return PextProjectExpense::whereIn('project_id', (array) $projectIds)
                ->where('fixedOrAdditional', $fixedOrAdditional)
                ->select('zone', DB::raw('SUM(amount) as total_amount'))
                ->groupBy('zone')
                ->pluck('total_amount', 'zone'); // 🔥 Devuelve un array asociativo [ 'zona1' => monto1, 'zona2' => monto2, ... ]
        };

        // Obtener los gastos de cada caso como arrays asociativos
        $currentExpensesFixed = $getExpensesByZone($currentProjects, 0);
        $currentExpensesAdditional = $getExpensesByZone($currentProjects, 1);
        $previousExpensesFixed = $previousProject ? $getExpensesByZone($previousProject, 0) : collect([]);
        $previousExpensesAdditional = $previousProject ? $getExpensesByZone($previousProject, 1) : collect([]);
        $annualExpensesFixed = $getExpensesByZone($annualProjects, 0);
        $annualExpensesAdditional = $getExpensesByZone($annualProjects, 1);

        // Obtener todas las zonas en orden
        $zones = PextConstants::getZone();

        // Crear arreglos con montos ordenados según las zonas
        $formatExpenses = function ($expenses) use ($zones) {
            return array_map(fn($zone) => $expenses[$zone] ?? 0, $zones);
        };

        return response()->json([
            'zones' => $zones,
            'current' => [
                'fixed' => $formatExpenses($currentExpensesFixed),
                'additional' => $formatExpenses($currentExpensesAdditional),
            ],
            'previous' => [
                'fixed' => $formatExpenses($previousExpensesFixed),
                'additional' => $formatExpenses($previousExpensesAdditional),
            ],
            'years' => [
                'fixed' => $formatExpenses($annualExpensesFixed),
                'additional' => $formatExpenses($annualExpensesAdditional),
            ]
        ]);
    }

    public function import_excel_expenses(Request $request)
    {
        $validateData = $request->validate([
            'project_id' => 'required',
            'fixedOrAdditional' => 'required',
            'file' => 'required',
        ]);
        try {
            $import = new PextProjectExpensesImport($validateData['project_id'], $validateData['fixedOrAdditional']);
            Excel::import($import, $request->file('file'));
            return response()->json([], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
