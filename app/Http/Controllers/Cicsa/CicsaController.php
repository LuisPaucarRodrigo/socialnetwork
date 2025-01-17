<?php

namespace App\Http\Controllers\Cicsa;

use App\Exports\CicsaProcess\AssignationExport;
use App\Exports\CicsaProcess\ChargeAreaExport;
use App\Exports\CicsaProcess\CicsaProcessExport;
use App\Exports\CicsaProcess\FeasibilitiesExport;
use App\Exports\CicsaProcess\InstallationExport;
use App\Exports\CicsaProcess\MaterialExport;
use App\Exports\CicsaProcess\MaterialsSummary;
use App\Exports\CicsaProcess\OCValidationExport;
use App\Exports\CicsaProcess\PurchaseOrderExport;
use App\Exports\CicsaProcess\ServiceOrderExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cicsa\StoreOrUpdateAssigantionRequest;
use App\Http\Requests\Cicsa\StoreOrUpdateChargeArea;
use App\Http\Requests\Cicsa\StoreOrUpdateFeasibilitiesRequest;
use App\Http\Requests\Cicsa\StoreOrUpdateInstallationRequest;
use App\Models\CicsaInstallation;
use App\Models\CicsaInstallationMaterial;
use App\Models\CicsaMaterialsItem;
use Illuminate\Http\Request;
use App\Http\Requests\Cicsa\StoreOrUpdateMaterialRequest;
use App\Http\Requests\Cicsa\StoreOrUpdatePurchaseOrderRequest;
use App\Imports\CicsaMaterialImport;
use App\Models\CicsaAssignation;
use App\Models\CicsaChargeArea;
use App\Models\CicsaFeasibility;
use App\Models\CicsaFeasibilityMaterial;
use App\Models\CicsaMaterial;
use App\Models\CicsaPurchaseOrder;
use App\Models\CicsaServiceOrder;
use App\Models\CicsaPurchaseOrderValidation;
use App\Models\CostLine;
use App\Models\ToolsGtd;
use App\Services\CicsaServices;
use Inertia\Inertia;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CicsaController extends Controller
{
    protected $cicsaService;

    public function __construct(CicsaServices $cicsaService)
    {
        $this->cicsaService = $cicsaService;
    }

    public function index($type)
    {
        $projects = CicsaAssignation::whereHas('project', function ($subQuery) use ($type) {
            $subQuery->where('cost_line_id', $type);
        })
            ->where(function ($query) {
                $query->whereDoesntHave('cicsa_charge_area')
                    ->orWhere(function ($query) {
                        $query->whereHas('cicsa_charge_area', function ($subQuery) {
                            $subQuery->where(function ($subQuery) {
                                $subQuery->whereNull('invoice_number')
                                    ->orWhereNull('invoice_date')
                                    ->orWhereNull('amount');
                            })
                                ->whereNull('deposit_date');
                        });
                    });
            })
            ->with(
                'cicsa_feasibility.cicsa_feasibility_materials',
                'cicsa_materials.cicsa_material_items',
                'cicsa_installation.cicsa_installation_materials',
                'cicsa_purchase_order',
                'cicsa_purchase_order_validation',
                'cicsa_service_order',
                'cicsa_charge_area',
                'project.cost_center'
            )

            ->paginate(15);
        $projects->getCollection()->each(function ($project) {
            $project->setAppends([
                'total_materials',
                'cicsa_project_status',
                'cicsa_administration_status',
                'cicsa_charge_status',
                'last_project_status_date',
                'last_administration_status_date',
                'last_charge_status_date',
            ]);
        });
        if($type == 2){
            $cost_line = CostLine::where('name', 'PEXT')->with('cost_center')->first();
        }
        if($type == 1){
            $cost_line = CostLine::where('name', 'PINT')->with('cost_center')->first();
        }
        return Inertia::render('Cicsa/CicsaIndex', [
            'projects' => $projects,
            'center_list' => $cost_line->cost_center,
            'type' => $type,
        ]);
    }

    public function exportCiscaProcess($type, $stages = "")
    {
        return Excel::download(new CicsaProcessExport($type, $stages), 'Proceso Cicsa ' . $stages . ' ' . date('d-m-Y') . '.xlsx');
    }

    public function search(Request $request, $type)
    {
        try {
            $stages = $request->typeStages;
            if ($stages === "Todos") {
                $projectsCicsa = CicsaAssignation::whereHas('project', function ($subQuery) use ($type) {
                    $subQuery->where('cost_line_id', $type);
                })
                    ->with(
                        'cicsa_feasibility.cicsa_feasibility_materials',
                        'cicsa_materials.cicsa_material_items',
                        'cicsa_installation.cicsa_installation_materials',
                        'cicsa_purchase_order',
                        'cicsa_purchase_order_validation',
                        'cicsa_service_order',
                        'cicsa_charge_area',
                        'project.cost_center'
                    );
            } else {
                $projectsCicsa = CicsaAssignation::whereHas('project', function ($subQuery) use ($type) {
                    $subQuery->where('cost_line_id', $type);
                })
                    ->with('project.cost_center');
                if ($stages === "Proyecto") {
                    $projectsCicsa->with(
                        'cicsa_feasibility.cicsa_feasibility_materials',
                        'cicsa_materials.cicsa_material_items',
                        'cicsa_installation.cicsa_installation_materials',
                    );
                } elseif ($stages === "Administracion") {
                    $projectsCicsa->with(
                        'cicsa_purchase_order',
                        'cicsa_purchase_order_validation',
                        'cicsa_service_order',
                    );
                } elseif ($stages === "Cobranza") {
                    $projectsCicsa->with(
                        [
                            'cicsa_purchase_order:id,oc_number,cicsa_assignation_id',
                            'cicsa_charge_area'
                        ]
                    );
                }
            }

            if ($request->opNoDate) {
                $projectsCicsa->where('assignation_date', null);
            }
            if ($request->opStartDate) {
                $projectsCicsa->where('assignation_date', '>=', $request->opStartDate);
            }
            if ($request->opEndDate) {
                $projectsCicsa->where('assignation_date', '<=', $request->opEndDate);
            }
            if (!empty($request->search)) {
                $search = $request->search;
                $searchTerms = explode(' ', $search);
                $projectsCicsa = $projectsCicsa->where(function ($query) use ($searchTerms) {
                    foreach ($searchTerms as $term) {
                        $query->where('project_name', 'like', "%$term%")
                            ->orWhere('project_code', 'like', "%$term%")
                            ->orWhere('cpe', 'like', "%$term%");
                    }
                });
            }
            if (count($request->cost_center) < 7) {
                $costCenter = $request->cost_center;
                $projectsCicsa = $projectsCicsa->whereHas('project.cost_center', function ($query) use ($costCenter) {
                    $query->whereIn('name', $costCenter);
                });
            }

            $projectsCicsa = $projectsCicsa->get();
            $projectsCicsa->each(function ($project) {
                $project->setAppends([
                    'total_materials',
                    'cicsa_project_status',
                    'cicsa_administration_status',
                    'cicsa_charge_status',
                    'last_project_status_date',
                    'last_administration_status_date',
                    'last_charge_status_date',
                ]);
            });
            if ($stages === "Administracion") {
                $projectsCicsa = $projectsCicsa->filter(function ($item) {
                    return $item->cicsa_project_status === 'Completado';
                });
            } elseif ($stages === "Cobranza") {
                $projectsCicsa = $projectsCicsa->filter(function ($item) {
                    return $item->cicsa_administration_status === 'Completado';
                });
            }

            if (count($request->state_charge_area) < 4) {
                $selectedPS = $request->state_charge_area;
                $projectsCicsa = $projectsCicsa->filter(function ($project) use ($selectedPS) {
                    return $project->cicsa_charge_area->contains(function ($chargeArea) use ($selectedPS) {
                        return in_array($chargeArea->state, $selectedPS);
                    });
                });
            }

            if (count($request->project_status) < 3) {
                $selectedPS = $request->project_status;
                $projectsCicsa = $projectsCicsa->filter(function ($item) use ($selectedPS) {
                    return in_array($item->cicsa_project_status, $selectedPS);
                });
            }

            if (count($request->administration_status) < 3) {
                $selectedPS = $request->administration_status;
                $projectsCicsa = $projectsCicsa->filter(function ($item) use ($selectedPS) {
                    return in_array($item->cicsa_administration_status, $selectedPS);
                });
            }

            if (count($request->charge_status) < 3) {
                $selectedPS = $request->charge_status;
                $projectsCicsa = $projectsCicsa->filter(function ($item) use ($selectedPS) {
                    return in_array($item->cicsa_charge_status, $selectedPS);
                });
            }

            return response()->json($projectsCicsa->values()->all(), 200);
        } catch (\Exception $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    public function destroy($ca_id)
    {
        try {
            CicsaAssignation::findOrFail($ca_id)->delete();
            return response()->noContent();
        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    public function indexAssignation(Request $request, $type, $searchCondition = null,)
    {
        if ($request->isMethod('get')) {
            $assignation = CicsaAssignation::whereHas('project', function ($subQuery) use ($type) {
                $subQuery->where('cost_line_id', $type);
            })
                ->with('project.cost_center')->orderBy('created_at', 'desc')->paginate();
            return Inertia::render('Cicsa/CicsaAssignation', [
                'assignation' => $assignation,
                'searchCondition' => $searchCondition,
                'type' => $type,
            ]);
        } elseif ($request->isMethod('post')) {
            $assignation = CicsaAssignation::whereHas('project', function ($subQuery) use ($type) {
                $subQuery->where('cost_line_id', $type);
            })
                ->with('project.cost_center')
                ->where(function ($query) use ($request) {
                    $query->orWhere('project_name', 'like', "%$request->searchQuery%")
                        ->orWhere('project_code', 'like', "%$request->searchQuery%")
                        ->orWhere('cpe', 'like', "%$request->searchQuery%");
                })
                ->get();
            return response()->json([
                'assignation' => $assignation,
            ]);
        }
    }

    public function exportAssignation($type)
    {
        return Excel::download(new AssignationExport($type), 'Asignación ' . date('d-m-Y') . '.xlsx');
    }

    public function indexFeasibilities(Request $request, $type, $searchCondition = null)
    {
        if ($request->isMethod('get')) {
            $feasibility = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe', 'project_id')->whereHas('project', function ($subQuery) use ($type) {
                $subQuery->where('cost_line_id', $type);
            })
                ->with('cicsa_feasibility.cicsa_feasibility_materials', 'project.cost_center')
                ->orderBy('assignation_date', 'desc')
                ->paginate(20);
            return Inertia::render('Cicsa/CicsaFeasibility', [
                'feasibility' => $feasibility,
                'searchCondition' => $searchCondition,
                'type' => $type,
            ]);
        } elseif ($request->isMethod('post')) {
            $feasibility = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe', 'project_id')->whereHas('project', function ($subQuery) use ($type) {
                $subQuery->where('cost_line_id', $type);
            })
                ->with('cicsa_feasibility.cicsa_feasibility_materials', 'project.cost_center')
                ->where(function ($query) use ($request) {
                    $query->orWhere('project_name', 'like', "%$request->searchQuery%")
                        ->orWhere('project_code', 'like', "%$request->searchQuery%")
                        ->orWhere('cpe', 'like', "%$request->searchQuery%");
                })
                ->get();
            return response()->json([
                'feasibility' => $feasibility
            ]);
        }
    }

    public function updateOrStoreFeasibilities(StoreOrUpdateFeasibilitiesRequest $request, $cicsa_assignation_id = null)
    {
        $validateData = $request->validated();
        $cicsaFeasibility = CicsaFeasibility::updateOrCreate(
            ['cicsa_assignation_id' => $cicsa_assignation_id],
            $validateData
        );
        $cicsaFeasibilityId = $cicsaFeasibility->id;
        foreach ($request->cicsa_feasibility_materials as $material) {
            $material['cicsa_feasibility_id'] = $cicsaFeasibilityId;
            CicsaFeasibilityMaterial::updateOrCreate(
                ['id' => isset($material['id']) ? $material['id'] : null],
                $material
            );
        }
        $cicsaFeasibility->load('cicsa_feasibility_materials');
        return response()->json($cicsaFeasibility, 200);
    }

    public function exportFeasibilities($type)
    {
        return Excel::download(new FeasibilitiesExport($type), 'Factibilidad ' . date('d-m-Y') . '.xlsx');
    }

    public function indexMaterial(Request $request, $type, $searchCondition = null)
    {
        if ($request->isMethod('get')) {
            $material = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe', 'project_id')
                ->whereHas('project', function ($subQuery) use ($type) {
                    $subQuery->where('cost_line_id', $type);
                })
                ->with('cicsa_feasibility.cicsa_feasibility_materials', 'cicsa_materials.cicsa_material_items', 'project.cost_center')
                ->orderBy('assignation_date', 'desc')
                ->paginate(20);
            return Inertia::render('Cicsa/CicsaMaterial', [
                'material' => $material,
                'searchCondition' => $searchCondition,
                'type' => $type
            ]);
        } elseif ($request->isMethod('post')) {
            $material = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe', 'project_id')
                ->with('cicsa_feasibility.cicsa_feasibility_materials', 'cicsa_materials.cicsa_material_items', 'project.cost_center')
                ->whereHas('project', function ($subQuery) use ($type) {
                    $subQuery->where('cost_line_id', $type);
                })
                ->where(function ($query) use ($request) {
                    $query->orWhere('project_name', 'like', "%$request->searchQuery%")
                        ->orWhere('project_code', 'like', "%$request->searchQuery%")
                        ->orWhere('cpe', 'like', "%$request->searchQuery%");
                })
                ->get();
            return response()->json([
                'material' => $material
            ]);
        }
    }

    public function storeMaterial(StoreOrUpdateMaterialRequest $request)
    {
        $validateData = $request->validated();
        $cicsaMaterial = CicsaMaterial::create(
            $validateData
        );
        if (isset($validateData['cicsa_material_items'])) {
            foreach ($validateData['cicsa_material_items'] as $item) {
                $item['cicsa_material_id'] = $cicsaMaterial->id;
                CicsaMaterialsItem::create($item);
            }
        }
        $cicsaMaterial->load('cicsa_material_items');
        return response()->json($cicsaMaterial, 200);
    }

    public function updateMaterial(StoreOrUpdateMaterialRequest $request, $cicsa_material_id = null)
    {
        $validateData = $request->validated();
        $cicsaMaterial = CicsaMaterial::updateOrCreate(
            ['id' => $cicsa_material_id],
            $validateData
        );
        if ($cicsaMaterial->cicsa_material_items) {
            CicsaMaterialsItem::where('cicsa_material_id', $cicsaMaterial->id)->delete();
        }

        foreach ($request->cicsa_material_items as $item) {
            $item['cicsa_material_id'] = $cicsaMaterial->id;
            CicsaMaterialsItem::create($item);
        }

        $cicsaMaterial->load('cicsa_material_items');
        return response()->json($cicsaMaterial, 200);
    }

    public function searchMaterial(Request $request)
    {
        $material = ToolsGtd::orWhere('code_ax', 'like', "%$request->searchQuery%")
            ->orWhere('name', 'like', "%$request->searchQuery%")
            ->orWhere('internal_reference', 'like', "%$request->searchQuery%")
            ->orWhere('unit', 'like', "%$request->searchQuery%")
            ->get();
        return response()->json([
            'materials' => $material
        ]);
    }

    public function exportMaterial($type)
    {
        return Excel::download(new MaterialExport($type), 'Materiales ' . date('d-m-Y') . '.xlsx');
    }

    public function importMaterial(Request $request)
    {
        try {
            if ($request->hasFile('document')) {
                $import = new CicsaMaterialImport();
                Excel::import($import, $request->file('document'));
                $data = $import->getData();
                return response()->json(
                    $data->toArray()
                );
            } else {
                return response()->json([
                    'errorMessage' => 'Ingrese un archivo Excel'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'errorMessage' => 'Error durante la Importacion: ' . $e->getMessage()
            ], 500);
        }
    }

    public function indexPurchaseOrder(Request $request, $type, $searchCondition = null)
    {
        if ($request->isMethod('get')) {
            $purchase_order = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe', 'project_id')
                ->with('cicsa_installation', 'cicsa_purchase_order', 'project.cost_center')
                // ->whereDoesntHave('cicsa_purchase_order_validation')
                ->whereHas('project', function ($subQuery) use ($type) {
                    $subQuery->where('cost_line_id', $type);
                })
                ->orderBy('assignation_date', 'desc')
                ->paginate(20);
            return Inertia::render('Cicsa/CicsaPurchaseOrder', [
                'purchaseOrder' => $purchase_order,
                'searchCondition' => $searchCondition,
                'type' => $type
            ]);
        } elseif ($request->isMethod('post')) {
            $searchQuery = $request->searchQuery;
            $purchase_order = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe', 'project_id')
                ->with('cicsa_installation', 'cicsa_purchase_order', 'project.cost_center')
                // ->whereDoesntHave('cicsa_purchase_order_validation')
                ->whereHas('project', function ($subQuery) use ($type) {
                    $subQuery->where('cost_line_id', $type);
                })
                ->where(function ($query) use ($searchQuery) {
                    $query->orWhere('project_name', 'like', "%$searchQuery%")
                        ->orWhere('project_code', 'like', "%$searchQuery%")
                        ->orWhere('cpe', 'like', "%$searchQuery%")
                        ->orWhere(function ($query) use ($searchQuery) {
                            $query->whereHas('cicsa_purchase_order', function ($query) use ($searchQuery) {
                                $query->where('oc_number', 'like', "%$searchQuery%")
                                    ->orWhere('observation', 'like', "%$searchQuery%");
                            });
                        });
                })
                ->get();
            return response()->json([
                'purchaseOrder' => $purchase_order
            ]);
        }
    }

    public function updateOrStorePurchaseOrder(StoreOrUpdatePurchaseOrderRequest $request, $cicsa_purchase_order_id = null)
    {
        $validateData = $request->validated();

        $document = null;
        DB::beginTransaction();
        try {
            if ($request->hasFile('document')) {
                $document = $request->file('document');
                $validateData['document'] = time() . '._' . $document->getClientOriginalName();
            }
            $validateData['amount'] = floatval($validateData['amount']);
            $purchase_order = CicsaPurchaseOrder::updateOrCreate(
                ['id' => $cicsa_purchase_order_id],
                $validateData
            );

            if (!$cicsa_purchase_order_id) {
                CicsaPurchaseOrderValidation::create([
                    'cicsa_assignation_id' => $purchase_order->cicsa_assignation_id,
                    'cicsa_purchase_order_id' => $purchase_order->id
                ]);
                CicsaServiceOrder::create([
                    'cicsa_assignation_id' => $purchase_order->cicsa_assignation_id,
                    'cicsa_purchase_order_id' => $purchase_order->id
                ]);
                CicsaChargeArea::create([
                    'cicsa_assignation_id' => $purchase_order->cicsa_assignation_id,
                    'cicsa_purchase_order_id' => $purchase_order->id
                ]);
            }
            DB::commit();
            if ($request->hasFile('document')) {
                $document->move(public_path('documents/cicsa/cicsaPurchaseOrder/'), $validateData['document']);
            }
            return response()->json($purchase_order, 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error', $e->getMessage()], 500);
        }
    }

    public function showDocument(CicsaPurchaseOrder $purchaseOrder)
    {
        $documentName = $purchaseOrder->document;
        $filePath = 'documents/cicsa/cicsaPurchaseOrder/' . $documentName;
        $path = public_path($filePath);
        if (file_exists($path)) {
            $url = url($filePath);
            return response()->json(['url' => $url]);
        }
        abort(404, 'Imagen no encontrada');
    }

    public function exportPurchaseOrder($type)
    {
        return Excel::download(new PurchaseOrderExport($type), 'Orden de Compra ' . date('d-m-Y') . '.xlsx');
    }


    public function indexInstallation(Request $request, $type, $searchCondition = null)
    {
        if ($request->isMethod('get')) {
            $installations = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe', 'project_id')
                ->with(
                    'cicsa_installation.cicsa_installation_materials',
                    'cicsa_installation.user',
                    'project.cost_center'
                )
                ->whereHas('project', function ($subQuery) use ($type) {
                    $subQuery->where('cost_line_id', $type);
                })
                ->orderBy('assignation_date', 'desc')
                ->paginate();
            $installations->getCollection()->each(function ($item) {
                $item->setAppends([
                    'total_materials',
                ]);
            });
            return Inertia::render('Cicsa/CicsaInstallation', [
                'installation' => $installations,
                'searchCondition' => $searchCondition,
                'type' => $type
            ]);
        } elseif ($request->isMethod('post')) {
            $installations = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe', 'project_id')
                ->with(
                    'cicsa_installation.cicsa_installation_materials',
                    'cicsa_installation.user',
                    'project.cost_center'
                )
                ->whereHas('project', function ($subQuery) use ($type) {
                    $subQuery->where('cost_line_id', $type);
                })
                ->where(function ($query) use ($request) {
                    $query->orWhere('project_name', 'like', "%$request->searchQuery%")
                        ->orWhere('project_code', 'like', "%$request->searchQuery%")
                        ->orWhere('cpe', 'like', "%$request->searchQuery%");
                })
                ->get();
            $installations->each->setAppends([
                'total_materials',
            ]);
            return response()->json($installations, 200);
        }
    }


    public function updateOrStoreInstallation(StoreOrUpdateInstallationRequest $request, $ci_id = null)
    {
        $validateData = $request->validated();
        try {
            $cicsaInstallation = CicsaInstallation::updateOrCreate(
                ['id' => $ci_id],
                $validateData
            );
            if ($ci_id) {
                CicsaInstallationMaterial::where('cicsa_installation_id', $ci_id)->delete();
            }
            foreach ($request->total_materials as $material) {
                $material['cicsa_installation_id'] = $cicsaInstallation->id;
                CicsaInstallationMaterial::create($material);
            }

            $cicsaInstallation->load('cicsa_installation_materials');
            return response()->json($cicsaInstallation, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function exportInstallation($type)
    {
        return Excel::download(new InstallationExport($type), 'Instalacion ' . date('d-m-Y') . '.xlsx');
    }

    public function indexOCValidation(Request $request, $type,  $searchCondition = null)
    {
        if ($request->isMethod('get')) {
            $purchase_validations = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe', 'project_id')
                ->with([
                    'cicsa_purchase_order_validation.cicsa_purchase_order' => function ($query) {
                        $query->select('id', 'oc_number');
                    },
                    'project.cost_center'
                ])
                ->whereHas('project', function ($subQuery) use ($type) {
                    $subQuery->where('cost_line_id', $type);
                })
                // ->whereDoesntHave('cicsa_service_order')
                // ->whereHas('cicsa_purchase_order', function ($query) {
                //     $query->whereNotNull('oc_date')
                //         ->whereNotNull('oc_number')
                //         ->where('master_format', 'Completado')
                //         ->where('item3456', 'Completado')
                //         ->where('budget', 'Completado');
                // })
                ->orderBy('assignation_date', 'desc')
                ->paginate(20);
            return Inertia::render('Cicsa/CicsaPurchaseOrderValidation', [
                'purchase_validation' => $purchase_validations,
                'searchCondition' => $searchCondition,
                'type' => $type
            ]);
        } elseif ($request->isMethod('post')) {

            $searchQuery = $request->searchQuery;
            $purchase_validations = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe', 'project_id')
                ->with([
                    'cicsa_purchase_order_validation.cicsa_purchase_order' => function ($query) {
                        $query->select('id', 'oc_number');
                    },
                    'project.cost_center'
                ])
                ->whereHas('project', function ($subQuery) use ($type) {
                    $subQuery->where('cost_line_id', $type);
                })
                // ->whereDoesntHave('cicsa_service_order')
                // ->whereHas('cicsa_purchase_order', function ($query) {
                //     $query->whereNotNull('oc_date')
                //         ->whereNotNull('oc_number')
                //         ->where('master_format', 'Completado')
                //         ->where('item3456', 'Completado')
                //         ->where('budget', 'Completado');
                // })
                ->where(function ($query) use ($searchQuery) {
                    $query->orWhere('project_name', 'like', "%$searchQuery%")
                        ->orWhere('project_code', 'like', "%$searchQuery%")
                        ->orWhere('cpe', 'like', "%$searchQuery%")
                        ->orWhereHas('cicsa_purchase_order', function ($query) use ($searchQuery) {
                            $query->where('oc_number', 'like', "%$searchQuery%");
                        })
                        ->orWhereHas('cicsa_purchase_order_validation', function ($query) use ($searchQuery) {
                            $query->where('observations', 'like', "%$searchQuery%");
                        });
                })
                ->get();
            return response()->json([
                'purchase_validation' => $purchase_validations,
            ]);
        }
    }

    public function storeOrUpdateOCValidation(Request $request, $cicsa_validation_order_id)
    {
        $validateData = $request->validate([
            'validation_date' => 'required',
            'materials_control' => 'required',
            'file_validation' => 'required',
            'supervisor' => 'required',
            'warehouse' => 'required',
            'boss' => 'required',
            'liquidator' => 'required',
            'superintendent' => 'required',
            'observations' => 'nullable',
            'user_name' => 'required',
            'user_id' => 'required',
        ]);
        $validationOc = CicsaPurchaseOrderValidation::with([
            'cicsa_purchase_order' => function ($query) {
                $query->select('id', 'oc_number');
            }
        ])->find($cicsa_validation_order_id);
        $validationOc->update($validateData);
        return response()->json($validationOc, 200);
    }

    public function exportOCValidation($type)
    {
        return Excel::download(new OCValidationExport($type), 'Validación de OC ' . date('d-m-Y') . '.xlsx');
    }

    // CicsaServiceOrder
    public function indexServiceOrder(Request $request, $type, $searchCondition = null)
    {
        if ($request->isMethod('get')) {
            $service_orders = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe', 'project_id')
                ->with([
                    'cicsa_service_order.cicsa_purchase_order' => function ($query) {
                        $query->select('id', 'oc_number');
                    },
                    'project.cost_center'
                ])
                ->whereHas('project', function ($subQuery) use ($type) {
                    $subQuery->where('cost_line_id', $type);
                })
                // ->whereDoesntHave('cicsa_charge_area')
                // ->whereHas('cicsa_purchase_order_validation',function($query){
                //     $query->where('file_validation','Completado')
                //     ->where('materials_control','Completado')
                //     ->where('supervisor','Completado')
                //     ->where('warehouse','Completado')
                //     ->where('boss','Completado')
                //     ->where('liquidator','Completado')
                //     ->where('superintendent','Completado');
                // })
                ->orderBy('assignation_date', 'desc')
                ->paginate(20);
            return Inertia::render('Cicsa/CicsaServiceOrder', [
                'service_order' => $service_orders,
                'searchCondition' => $searchCondition,
                'type' => $type
            ]);
        } elseif ($request->isMethod('post')) {
            $searchQuery = $request->searchQuery;
            $service_orders = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe', 'project_id')
                ->with([
                    'cicsa_service_order.cicsa_purchase_order' => function ($query) {
                        $query->select('id', 'oc_number');
                    },
                    'project.cost_center'
                ])
                ->whereHas('project', function ($subQuery) use ($type) {
                    $subQuery->where('cost_line_id', $type);
                })
                // ->whereDoesntHave('cicsa_charge_area')
                // ->whereHas('cicsa_purchase_order_validation',function($query){
                //     $query->where('file_validation','Completado')
                //     ->where('materials_control','Completado')
                //     ->where('supervisor','Completado')
                //     ->where('warehouse','Completado')
                //     ->where('boss','Completado')
                //     ->where('liquidator','Completado')
                //     ->where('superintendent','Completado');
                // })
                ->where(function ($query) use ($searchQuery) {
                    $query->orWhere('project_name', 'like', "%$searchQuery%")
                        ->orWhere('project_code', 'like', "%$searchQuery%")
                        ->orWhere('cpe', 'like', "%$searchQuery%")
                        ->orWhere(function ($query) use ($searchQuery) {
                            $query->whereHas('cicsa_purchase_order', function ($query) use ($searchQuery) {
                                $query->where('oc_number', 'like', "%$searchQuery%");
                            });
                        });
                })
                ->get();
            return response()->json([
                'service_order' => $service_orders,
            ]);
        }
    }

    public function updateServiceOrder(Request $request, $cicsa_service_order_id)
    {
        $validateData = $request->validate([
            'service_order_date' => 'required',
            'service_order' => 'required',
            'estimate_sheet' => 'required',
            'purchase_order' => 'required',
            'pdf_invoice' => 'required',
            'zip_invoice' => 'required',
            'document' => 'required',
            'document_invoice' => 'nullable',
            'user_name' => 'required',
            'user_id' => 'required',
        ]);
        $document = null;
        $document_invoice = null;
        DB::beginTransaction();
        try {
            if ($request->hasFile('document')) {
                $document = $request->file('document');
                $validateData['document'] = time() . '._' . $document->getClientOriginalName();
            }
            if ($request->hasFile('document_invoice')) {
                $document_invoice = $request->file('document_invoice');
                $validateData['document_invoice'] = time() . '._' . $document_invoice->getClientOriginalName();
            }
            $serviceOrderOc = CicsaServiceOrder::with([
                'cicsa_purchase_order' => function ($query) {
                    $query->select('id', 'oc_number');
                }
            ])->find($cicsa_service_order_id);
            $serviceOrderOc->update($validateData);
            DB::commit();
            if ($request->hasFile('document')) {
                $document->move(public_path('documents/cicsa/cicsaServiceOrder/docsOS/'), $validateData['document']);
            }
            if ($request->hasFile('document_invoice')) {
                $document_invoice->move(public_path('documents/cicsa/cicsaServiceOrder/docsFac/'), $validateData['document_invoice']);
            }
            return response()->json($serviceOrderOc, 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error', $e->getMessage()], 500);
        }
    }

    public function showServiceDocument(CicsaServiceOrder $serviceOrder, $doc)
    {
        $documentName = null;
        try {
            $documentName = $doc === "OS" ? 'docsOS/' . $serviceOrder->document : 'docsFac/' . $serviceOrder->document_invoice;
            $filePath = 'documents/cicsa/cicsaServiceOrder/' . $documentName;
            $path = public_path($filePath);
            if (file_exists($path)) {
                $url = url($filePath);
                return response()->json(['url' => $url]);
            }
        } catch (Exception $e) {
            return response()->json(['error', $e->getMessage()], 500);
        }
    }

    public function exportServiceOrder($type)
    {
        return Excel::download(new ServiceOrderExport($type), 'Orden de Servicio ' . date('d-m-Y') . '.xlsx');
    }

    //CicsaChargeArea
    public function indexChargeArea(Request $request, $type, $searchCondition = null)
    {
        if ($request->isMethod('get')) {
            $charge_areas = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe', 'project_id')
                ->with([
                    'cicsa_charge_area.cicsa_purchase_order' => function ($query) {
                        $query->select('id', 'oc_number')
                            ->with(['cicsa_service_order:id,document_invoice,cicsa_purchase_order_id']);
                    },
                    'project.cost_center'
                ])
                ->whereHas('project', function ($subQuery) use ($type) {
                    $subQuery->where('cost_line_id', $type);
                })
                ->orderBy('assignation_date', 'desc')
                ->paginate(20);
            return Inertia::render('Cicsa/CicsaChargeArea', [
                'charge_area' => $charge_areas,
                'searchCondition' => $searchCondition,
                'type' => $type
            ]);
        } elseif ($request->isMethod('post')) {
            $searchQuery = $request->searchQuery;
            $charge_areas = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe', 'project_id')
                ->with([
                    'cicsa_charge_area.cicsa_purchase_order' => function ($query) {
                        $query->select('id', 'oc_number')
                            ->with(['cicsa_service_order:id,document_invoice,cicsa_purchase_order_id']);
                    },
                    'project.cost_center'
                ])
                ->whereHas('project', function ($subQuery) use ($type) {
                    $subQuery->where('cost_line_id', $type);
                })
                // ->whereHas('cicsa_service_order',function($query){
                //     $query->where('service_order','Completado')
                //     ->where('estimate_sheet','Completado')
                //     ->where('purchase_order','Completado')
                //     ->where('pdf_invoice','Completado')
                //     ->where('zip_invoice','Completado');
                // })
                ->where(function ($query) use ($searchQuery) {
                    $query->orWhere('project_name', 'like', "%$searchQuery%")
                        ->orWhere('project_code', 'like', "%$searchQuery%")
                        ->orWhere('cpe', 'like', "%$searchQuery%")
                        ->orWhere(function ($query) use ($searchQuery) {
                            $query->whereHas('cicsa_purchase_order', function ($query) use ($searchQuery) {
                                $query->where('oc_number', 'like', "%$searchQuery%");
                            });
                        })
                        ->orWhere(function ($query) use ($searchQuery) {
                            $query->whereHas('cicsa_charge_area', function ($query) use ($searchQuery) {
                                $query->where('invoice_number', 'like', "%$searchQuery%");
                            });
                        });
                })

                ->get();
            return response()->json([
                'charge_area' => $charge_areas,
            ]);
        }
    }

    public function updateChargeArea(Request $request, $cicsa_charge_area_id)
    {
        $validateData = $request->validate([
            'invoice_number' => 'nullable',
            'invoice_date' => 'nullable',
            'credit_to' => 'nullable|min:0',
            'deposit_date' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value && Carbon::parse($value)->lt(Carbon::parse($request->invoice_date))) {
                        $fail('La fecha de abono debe ser mayor o igual que la fecha de la factura.');
                    }
                }
            ],
            'document' => 'nullable',
            'amount' => 'required',
            'state_detraction' => 'required|string',
            'transaction_number_current' => 'nullable',
            'checking_account_amount' => 'nullable|string',
            'deposit_date_bank' => 'nullable|date',
            'transaction_number_bank' => 'nullable',
            'amount_bank' => 'nullable|numeric',
            'user_name' => 'required',
            'user_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $validateData['state_detraction'] = $validateData['state_detraction'] === 'true' ? 1 : 0;
            $validateData['amount'] = floatval($validateData['amount']);
            $validateData['checking_account_amount'] = floatval($validateData['checking_account_amount']);
            $validateData['amount_bank'] = floatval($validateData['amount_bank']);
            if ($request->hasFile('document')) {
                $document = $request->file('document');
                $validateData['document'] = time() . '._' . $document->getClientOriginalName();
            }
            $chargeAreaOc = CicsaChargeArea::with([
                'cicsa_purchase_order' => function ($query) {
                    $query->select('id', 'oc_number');
                }
            ])->find($cicsa_charge_area_id);
            $chargeAreaOc->update($validateData);

            DB::commit();
            if ($request->hasFile('document')) {
                $document->move(public_path('documents/cicsa/cicsaChargeAreaOrder/'), $validateData['document']);
            }
            return response()->json($chargeAreaOc, 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error', $e->getMessage()], 500);
        }
    }

    public function showChargeAreaDocument(CicsaChargeArea $chargeAreaOrder)
    {
        $documentName = $chargeAreaOrder->document;
        $filePath = 'documents/cicsa/cicsaChargeAreaOrder/' . $documentName;
        $path = public_path($filePath);
        if (file_exists($path)) {
            $url = url($filePath);
            return response()->json(['url' => $url]);
        }
        abort(404, 'Imagen no encontrada');
    }

    public function exportChargeArea()
    {
        return Excel::download(new ChargeAreaExport, 'Cobranza ' . date('d-m-Y') . '.xlsx');
    }

    public function exportMaterialsSummary($ca_id)
    {
        return Excel::download(new MaterialsSummary($ca_id), 'ResumenMateriales.xlsx');
    }
}
