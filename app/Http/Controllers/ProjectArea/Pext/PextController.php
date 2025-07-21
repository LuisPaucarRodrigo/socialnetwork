<?php

namespace App\Http\Controllers\ProjectArea\Pext;

use App\Constants\PextConstants;
use App\Constants\PintConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\PextProjectRequest\StoreOrUpdateAssignationRequest;
use App\Models\CicsaAssignation;
use App\Models\Project;
use App\Models\ProjectQuote;
use App\Models\ProjectQuoteValuation;
use App\Models\Provider;
use App\Services\PextProjectServices;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

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
            $project = $this->pextServices->getProject(null)->paginate();
            return Inertia::render('ProjectArea/ProjectManagement/Pext/Monthly/PextProjectMonthly', [
                'project' => $project,
            ]);
        } elseif ($request->isMethod('post')) {
            $searchQuery = $request->searchQuery;
            $project = $this->pextServices->searchProjectMonthly(null, $searchQuery);
            return response()->json($project, 200);
        }
    }

    public function requestProjectOrPreproject()
    {
        $pro = $this->pextServices->getProjectOrProject();
        return response()->json($pro, 200);
    }

    public function historial_pext(Request $request)
    {
        if ($request->isMethod('get')) {
            $project = $this->pextServices->getProject(true)->paginate();
            return Inertia::render('ProjectArea/ProjectManagement/Pext/Monthly/Historial/PextProjectHistorial', [
                'project' => $project,
            ]);
        } elseif ($request->isMethod('post')) {
            $searchQuery = $request->searchQuery;
            $project = $this->pextServices->searchProjectMonthly(true, $searchQuery);
            return response()->json($project, 200);
        }
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

    public function index_expenses($project_id, $fixedOrAdditional, $status = null)
    {
        $expense = $this->pextServices->project_expenses_base($project_id, $fixedOrAdditional)
            ->paginate();
        $expense->each(function ($exp) {
            $exp->setAppends(['real_amount', 'real_state']);
        });
        $providers = Provider::select('id', 'ruc', 'company_name')->get();
        return Inertia::render(
            'ProjectArea/ProjectManagement/Pext/Monthly/Expenses/PextExpenses',
            [
                'expense' => $expense,
                'providers' => $providers,
                'project_id' => $project_id,
                'status' => $status,
                'zones' => PextConstants::getZone(),
                'docTypes' => PextConstants::getDocumentsType(),
                'expenseTypesFixed' => PextConstants::getExpenseTypeFixed(),
                'expenseTypesAdditional' => PextConstants::getExpenseType(),
                'fixedOrAdditional' => json_decode($fixedOrAdditional)
            ]
        );
    }

    public function index_additional(Request $request, $type, $searchCondition = null,)
    {
        if ($request->isMethod('get')) {
            $project = $this->pextServices->index_additional_base($type, 1);
            $project = $type == 2 ? $project->get() : $project->get();
            $project = $this->pextServices->addCalculated($project);
            $project = $project->each(function ($item) {
                return $item->cicsa_charge_status !== 'Completado';
            });
            $cost_line = $this->pextServices->costCenter($type);
            $zones = $type == 1 ? PintConstants::mobileZones() : PextConstants::getZone();

            return Inertia::render('ProjectArea/ProjectManagement/Pext/Additional/ProjectAdditional', [
                'project' => $project,
                'cost_line' => $cost_line,
                'searchCondition' => $searchCondition,
                'type' => $type,
                'optionZones' => $zones
            ]);
        } elseif ($request->isMethod('post')) {
            $project = $this->pextServices->index_additional_base($type, 1);
            $project = $this->pextServices->searchBase($project, $request->searchQuery)->get();
            $project = $this->pextServices->addCalculated($project);
            if ($request->statusProject) {
                $project = $project->filter(function ($item) {
                    return $item->cicsa_charge_status === 'Completado';
                });
            } else {
                $project = $project->filter(function ($item) {
                    return $item->cicsa_charge_status !== 'Completado';
                });
            }
            return response()->json($project, 200);
        }
    }


    public function index_additional_rejected(Request $request, $type)
    {
        if ($request->isMethod('get')) {
            $project = $this->pextServices->index_additional_base($type, 0)->paginate();
            $cost_line = $this->pextServices->costCenter($type);
            return Inertia::render('ProjectArea/ProjectManagement/Pext/Additional/ProjectAdditionalRejected', [
                'project' => $project,
                'cost_line' => $cost_line,
                'type' => $type,
            ]);
        } elseif ($request->isMethod('post')) {
            $project = $this->pextServices->index_additional_base($type, 0);
            $project = $this->pextServices->searchBase($project, $request->searchQuery)->get();
            return response()->json($project, 200);
        }
    }

    public function rejectProjectAdditional(Request $request, $pa_id)
    {
        $is_accepted = $request->action === "rejected" ? 0 : 1;
        $rg = Project::find($pa_id);
        $rg->update(['is_accepted' => $is_accepted]);
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
        $cicsaAssignation->load('project.cost_center', 'project.project_quote.project_quote_valuations');
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
}
