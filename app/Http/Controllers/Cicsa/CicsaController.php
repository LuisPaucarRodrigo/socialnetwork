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
use App\Http\Requests\Cicsa\StoreOrUpdateAssignationRequest;
use App\Http\Requests\Cicsa\StoreOrUpdateChargeArea;
use App\Http\Requests\Cicsa\StoreOrUpdateFeasibilitiesRequest;
use App\Http\Requests\Cicsa\StoreOrUpdateInstallationRequest;
use App\Models\CicsaInstallation;
use App\Models\CicsaInstallationMaterial;
use App\Models\CicsaMaterialsItem;
use Illuminate\Http\Request;
use App\Http\Requests\Cicsa\StoreOrUpdateMaterialRequest;
use App\Http\Requests\Cicsa\StoreOrUpdateOCValidation;
use App\Http\Requests\Cicsa\StoreOrUpdatePurchaseOrderRequest;
use App\Http\Requests\Cicsa\StoreOrUpdateServiceOrder;
use App\Imports\CicsaMaterialImport;
use App\Models\CicsaAssignation;
use App\Models\CicsaChargeArea;
use App\Models\CicsaFeasibility;
use App\Models\CicsaFeasibilityMaterial;
use App\Models\CicsaMaterial;
use App\Models\CicsaPurchaseOrder;
use App\Models\CicsaServiceOrder;
use App\Models\CicsaPurchaseOrderValidation;
use App\Models\PextProjectExpense;
use App\Models\Project;
use App\Models\ToolsGtd;
use App\Services\CicsaServices;
use Inertia\Inertia;
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
        $projects = $this->cicsaService->cicsaBaseQuery($type);
        $projects = $projects->where(function ($query) {
            $query->whereDoesntHave('cicsa_charge_area')
                ->orWhereHas('cicsa_charge_area', function ($subQuery) {
                    $subQuery->where(function ($q) {
                        $q->whereNull('invoice_number')
                            ->orWhereNull('invoice_date')
                            ->orWhereNull('amount');
                    })
                        ->whereNull('deposit_date');
                });
        });
        $projects = $this->cicsaService->addRelations($projects)->paginate(20);
        $projects = $this->cicsaService->addCalculatedFields($projects);
        $cost_center = $this->cicsaService->differentialIndex($type);

        return Inertia::render('Cicsa/CicsaIndex', [
            'projects' => $projects,
            'center_list' => $cost_center,
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
                $projectsCicsa = $this->cicsaService->cicsaBaseQuery($type);
                $projectsCicsa = $this->cicsaService->addRelations($projectsCicsa);
            } else {
                $projectsCicsa = $this->cicsaService->cicsaBaseQuery($type);
                $projectsCicsa->with('project.cost_center');
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

            $projectsCicsa = $this->cicsaService->filteredAdvance($request, $projectsCicsa)->get();
            $projectsCicsa = $this->cicsaService->addCalculatedFields($projectsCicsa);
            $projectsCicsa = $this->cicsaService->filteredCalculated($stages, $projectsCicsa);
            $projectsCicsa = $this->cicsaService->filteredExternal($request, $projectsCicsa);

            return response()->json($projectsCicsa->values()->all(), 200);
        } catch (\Exception $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    public function destroy($ca_id)
    {
        try {
            $cicsa = CicsaAssignation::findOrFail($ca_id);
            $expenses = PextProjectExpense::where('project_id', $cicsa->project_id)->exists();
            if ($expenses) {
                return response()->json(
                    ['errorMessage' => "No se puede eliminar porque hay gastos asociados al proyecto"],
                    500
                );
            }
            Project::findOrFail($cicsa->project_id)->delete();
            return response()->json([], 200);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    public function indexAssignation(Request $request, $type, $searchCondition = null)
    {
        if ($request->isMethod('get')) {
            $assignation = $this->cicsaService->baseAssignation($type)->paginate(20);
            return Inertia::render('Cicsa/Assignation/CicsaAssignation', [
                'assignation' => $assignation,
                'searchCondition' => $searchCondition,
                'type' => $type,
            ]);
        } elseif ($request->isMethod('post')) {
            $assignation = $this->cicsaService->searchAssignation($request, $type)->get();
            return response()->json([
                'assignation' => $assignation,
            ]);
        }
    }

    public function updateAssignation(StoreOrUpdateAssignationRequest $request, $cicsa_assignation_id)
    {
        $validateData = $request->validated();
        $assignation = $this->cicsaService->updateAssignation($validateData, $cicsa_assignation_id);
        return response()->json($assignation, 200);
    }

    public function exportAssignation($type)
    {
        return Excel::download(new AssignationExport($type), 'Asignación ' . date('d-m-Y') . '.xlsx');
    }

    public function indexFeasibilities(Request $request, $type, $searchCondition = null)
    {
        if ($request->isMethod('get')) {
            $feasibility = $this->cicsaService->baseFeasibilities($type)->paginate(20);
            return Inertia::render('Cicsa/CicsaFeasibility', [
                'feasibility' => $feasibility,
                'searchCondition' => $searchCondition,
                'type' => $type,
            ]);
        } elseif ($request->isMethod('post')) {
            $feasibility = $this->cicsaService->searchFeasibilities($request, $type)->get();
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
            $material = $this->cicsaService->baseMaterial($type)
                ->paginate(20);
            return Inertia::render('Cicsa/CicsaMaterial', [
                'material' => $material,
                'searchCondition' => $searchCondition,
                'type' => $type
            ]);
        } elseif ($request->isMethod('post')) {
            $material = $this->cicsaService->searchMaterial($request, $type)
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
        } catch (Exception $e) {
            return response()->json([
                'errorMessage' => 'Error durante la Importacion: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteMaterial($c_m_id)
    {
        try {
            $material = CicsaMaterial::findOrFail($c_m_id);
            $material->delete();
            $materialItems = CicsaMaterialsItem::where('cicsa_material_id', $c_m_id)->get();
            foreach ($materialItems as $item) {
                $item->delete();
            }
            return response()->json(['msg' => true]);
        } catch (Exception $e) {
            return response()->json([
                'errorMessage' => 'Error durante la eliminación: ' . $e->getMessage()
            ], 500);
        }
    }




    public function indexPurchaseOrder(Request $request, $type, $searchCondition = null)
    {
        if ($request->isMethod('get')) {
            $purchase_order = $this->cicsaService->basePurchaseOrder($type)
                ->paginate(20);
            return Inertia::render('Cicsa/CicsaPurchaseOrder', [
                'purchaseOrder' => $purchase_order,
                'searchCondition' => $searchCondition,
                'type' => $type
            ]);
        } elseif ($request->isMethod('post')) {
            $purchase_order = $this->cicsaService->searchPurchaseOrder($request, $type)
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
            $installations = $this->cicsaService->baseInstallation($type)->paginate(20);
            $installations->each(function ($item) {
                $item->setAppends([
                    'total_materials'
                ]);
            });
            return Inertia::render('Cicsa/CicsaInstallation', [
                'installation' => $installations,
                'searchCondition' => $searchCondition,
                'type' => $type
            ]);
        } elseif ($request->isMethod('post')) {
            $installations = $this->cicsaService->searchInstallation($request, $type)
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
            $purchase_validations = $this->cicsaService->baseOCValidation($type)
                ->paginate(20);
            return Inertia::render('Cicsa/CicsaPurchaseOrderValidation', [
                'purchase_validation' => $purchase_validations,
                'searchCondition' => $searchCondition,
                'type' => $type
            ]);
        } elseif ($request->isMethod('post')) {
            $purchase_validations = $this->cicsaService->searchOCValidation($request, $type)
                ->get();
            return response()->json([
                'purchase_validation' => $purchase_validations,
            ]);
        }
    }

    public function storeOrUpdateOCValidation(StoreOrUpdateOCValidation $request, $cicsa_validation_order_id)
    {
        $validateData = $request->validated();
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
            $service_orders = $this->cicsaService->baseServiceOrder($type)
                ->paginate(20);
            return Inertia::render('Cicsa/CicsaServiceOrder', [
                'service_order' => $service_orders,
                'searchCondition' => $searchCondition,
                'type' => $type
            ]);
        } elseif ($request->isMethod('post')) {
            $service_orders = $this->cicsaService->searchServiceOrder($request, $type)
                ->get();
            return response()->json([
                'service_order' => $service_orders,
            ]);
        }
    }

    public function updateServiceOrder(StoreOrUpdateServiceOrder $request, $cicsa_service_order_id)
    {
        $validateData = $request->validated();
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
            $charge_areas = $this->cicsaService->baseChargeArea($type)
                ->paginate(20);
            return Inertia::render('Cicsa/CicsaChargeArea', [
                'charge_area' => $charge_areas,
                'searchCondition' => $searchCondition,
                'type' => $type
            ]);
        } elseif ($request->isMethod('post')) {
            $charge_areas = $this->cicsaService->searchChargeArea($request, $type)
                ->get();
            return response()->json([
                'charge_area' => $charge_areas,
            ]);
        }
    }

    public function updateChargeArea(StoreOrUpdateChargeArea $request, $cicsa_charge_area_id)
    {
        $validateData = $request->validated();
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

    public function exportChargeArea($cost_line_id)
    {
        // dd($cost_line_id);
        return Excel::download(new ChargeAreaExport($cost_line_id), 'Cobranza ' . date('d-m-Y') . '.xlsx');
    }

    public function exportMaterialsSummary($ca_id)
    {
        return Excel::download(new MaterialsSummary($ca_id), 'ResumenMateriales.xlsx');
    }
}
