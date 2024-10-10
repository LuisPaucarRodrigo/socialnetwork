<?php

namespace App\Http\Controllers\Cicsa;

use App\Exports\CicsaProcess\AssignationExport;
use App\Exports\CicsaProcess\ChargeAreaExport;
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
use App\Models\ToolsGtd;
use Inertia\Inertia;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class CicsaController extends Controller
{
    public function index()
    {
        $projects = CicsaAssignation::where(function ($query) {
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
                'cicsa_charge_area'
            )

            ->paginate(10);
        return Inertia::render('Cicsa/CicsaIndex', [
            'projects' => $projects
        ]);
    }

    public function search(Request $request)
    {
        $projectsCicsa = CicsaAssignation::where(function ($query) {
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
                'cicsa_charge_area'
            );

        if (!empty($request->assignation_date)) {
            $assignationDate = $request->assignation_date;
            if (!empty($request->project_deadline)) {
                $deadLineDate = $request->project_deadline;
                $projectsCicsa = $projectsCicsa->where('assignation_date', '>', $assignationDate)
                    ->where('project_deadline', '<', $deadLineDate);
            } else {
                $projectsCicsa = $projectsCicsa->where('assignation_date', '>', $assignationDate);
            }
        }
        if (!empty($request->project_deadline)) {
            $selectedPS = $request->project_deadline;
            $projectsCicsa = $projectsCicsa->where('project_deadline', '<', $request->project_deadline);
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

        $projectsCicsa = $projectsCicsa->get();

        if (count($request->project_status) < 3) {
            $selectedPS = $request->project_status;
            $projectsCicsa = $projectsCicsa->filter(function ($item) use ($selectedPS) {
                return in_array($item->cicsa_project_status, $selectedPS);
            });
        }
        if (count($request->charge_status) < 3) {
            $selectedPS = $request->charge_status;
            $projectsCicsa = $projectsCicsa->filter(function ($item) use ($selectedPS) {
                return in_array($item->cicsa_charge_status, $selectedPS);
            });
        }

        return response()->json($projectsCicsa->values()->all(), 200);
    }



    public function destroy($ca_id)
    {
        CicsaAssignation::findOrFail($ca_id)->delete();
        return redirect()->back();
    }

    public function chargeCicsa(Request $request)
    {
        if ($request->isMethod('get')) {
            $charge_areas = CicsaChargeArea::with('cicsa_purchase_order', 'cicsa_assignation')
                ->whereNotNull('invoice_number')
                ->whereNotNull('invoice_date')
                ->whereNotNull('amount')
                ->where(function ($query) {
                    $query->orWhereNull('checking_account_amount')
                        ->orWhereNull('amount_bank');
                });
            $dd = $charge_areas->get();
            return Inertia::render('Cicsa/CicsaCollect', [
                'charge_areas' => $charge_areas->paginate(20),
                'total_amount' => $dd->sum('amount')
            ]);
        } else {
            $item = $request->searchQuery;
            $charge_areas = CicsaChargeArea::with('cicsa_purchase_order', 'cicsa_assignation')
                ->where(function ($a) use ($item) {
                    $a->whereNotNull('invoice_number')
                        ->whereNotNull('invoice_date')
                        ->whereNotNull('amount')
                        ->where(function ($b) use ($item) {
                            $b->whereHas('cicsa_assignation', function ($c) use ($item) {
                                $c->where('project_name', 'like', "%$item%")
                                    ->orWhere('project_code', 'like', "%$item%")
                                    ->orWhere('cpe', 'like', "%$item%");
                            });
                        });
                });


            $dd = $charge_areas->get();
            return response()->json([
                'charge_areas' => $charge_areas->paginate(20),
                'total_amount' => $dd->sum('amount')
            ], 200);
        }
    }

    public function indexAssignation(Request $request)
    {
        if ($request->isMethod('get')) {
            $assignation = CicsaAssignation::orderBy('created_at', 'desc')->paginate();
            return Inertia::render('Cicsa/CicsaAssignation', [
                'assignation' => $assignation
            ]);
        } elseif ($request->isMethod('post')) {
            $assignation = CicsaAssignation::orWhere('project_name', 'like', "%$request->searchQuery%")
                ->orWhere('project_code', 'like', "%$request->searchQuery%")
                ->orWhere('cpe', 'like', "%$request->searchQuery%")
                ->get();
            return response()->json([
                'assignation' => $assignation,
            ]);
        }
    }

    public function updateOrStoreAssignation(StoreOrUpdateAssigantionRequest $request, $cicsa_assignation_id = null)
    {
        $validateData = $request->validated();
        $cicsa_assigantion = CicsaAssignation::updateOrCreate(
            ['id' => $cicsa_assignation_id],
            $validateData
        );
        return response()->json($cicsa_assigantion, 200);
    }

    public function exportAssignation()
    {
        return Excel::download(new AssignationExport, 'Asignación ' . date('m-Y') . '.xlsx');
    }

    public function indexFeasibilities(Request $request)
    {
        if ($request->isMethod('get')) {
            $feasibility = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe')
                ->with('cicsa_feasibility.cicsa_feasibility_materials')
                ->orderBy('assignation_date', 'desc')
                ->paginate(20);
            return Inertia::render('Cicsa/CicsaFeasibility', [
                'feasibility' => $feasibility
            ]);
        } elseif ($request->isMethod('post')) {
            $feasibility = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe')
                ->with('cicsa_feasibility.cicsa_feasibility_materials')
                ->orWhere('project_name', 'like', "%$request->searchQuery%")
                ->orWhere('project_code', 'like', "%$request->searchQuery%")
                ->orWhere('cpe', 'like', "%$request->searchQuery%")
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

    public function exportFeasibilities()
    {
        return Excel::download(new FeasibilitiesExport, 'Factibilidad ' . date('m-Y') . '.xlsx');
    }

    public function indexMaterial(Request $request)
    {
        if ($request->isMethod('get')) {
            $material = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe')
                ->with('cicsa_feasibility.cicsa_feasibility_materials', 'cicsa_materials.cicsa_material_items')
                ->orderBy('assignation_date', 'desc')
                ->paginate(20);
            return Inertia::render('Cicsa/CicsaMaterial', [
                'material' => $material
            ]);
        } elseif ($request->isMethod('post')) {
            $material = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe')
                ->with('cicsa_feasibility.cicsa_feasibility_materials', 'cicsa_materials.cicsa_material_items')
                ->orWhere('project_name', 'like', "%$request->searchQuery%")
                ->orWhere('project_code', 'like', "%$request->searchQuery%")
                ->orWhere('cpe', 'like', "%$request->searchQuery%")
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

    public function exportMaterial()
    {
        return Excel::download(new MaterialExport, 'Materiales ' . date('m-Y') . '.xlsx');
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

    public function indexPurchaseOrder(Request $request)
    {
        if ($request->isMethod('get')) {
            $purchase_order = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe')
                ->with('cicsa_purchase_order')
                // ->whereDoesntHave('cicsa_purchase_order_validation')
                ->orderBy('assignation_date', 'desc')
                ->paginate(20);
            return Inertia::render('Cicsa/CicsaPurchaseOrder', [
                'purchaseOrder' => $purchase_order
            ]);
        } elseif ($request->isMethod('post')) {
            $searchQuery = $request->searchQuery;
            $purchase_order = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe')
                ->with('cicsa_purchase_order')
                // ->whereDoesntHave('cicsa_purchase_order_validation')
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
                'purchaseOrder' => $purchase_order
            ]);
        }
    }

    public function updateOrStorePurchaseOrder(StoreOrUpdatePurchaseOrderRequest $request, $cicsa_purchase_order_id = null)
    {
        DB::beginTransaction();
        try {
            $validateData = $request->validated();
            $purchase_order_id = CicsaPurchaseOrder::updateOrCreate(
                ['id' => $cicsa_purchase_order_id],
                $validateData
            );
            if (!$cicsa_purchase_order_id) {
                CicsaPurchaseOrderValidation::create([
                    'cicsa_assignation_id' => $purchase_order_id->cicsa_assignation_id,
                    'cicsa_purchase_order_id' => $purchase_order_id->id
                ]);
                CicsaServiceOrder::create([
                    'cicsa_assignation_id' => $purchase_order_id->cicsa_assignation_id,
                    'cicsa_purchase_order_id' => $purchase_order_id->id
                ]);
                CicsaChargeArea::create([
                    'cicsa_assignation_id' => $purchase_order_id->cicsa_assignation_id,
                    'cicsa_purchase_order_id' => $purchase_order_id->id
                ]);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function exportPurchaseOrder()
    {
        return Excel::download(new PurchaseOrderExport, 'Orden de Compra ' . date('m-Y') . '.xlsx');
    }


    public function indexInstallation(Request $request)
    {
        if ($request->isMethod('get')) {
            $installations = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe')
                ->with(
                    'cicsa_installation.cicsa_installation_materials',
                    'cicsa_installation.user'
                )
                ->orderBy('assignation_date', 'desc')
                ->paginate();
            return Inertia::render('Cicsa/CicsaInstallation', [
                'installation' => $installations
            ]);
        } elseif ($request->isMethod('post')) {
            $installations = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe')
                ->with(
                    'cicsa_installation.cicsa_installation_materials',
                    'cicsa_installation.user'
                )
                ->orWhere('project_name', 'like', "%$request->searchQuery%")
                ->orWhere('project_code', 'like', "%$request->searchQuery%")
                ->orWhere('cpe', 'like', "%$request->searchQuery%")
                ->get();
            return response()->json([
                'installation' => $installations
            ]);
        }
    }


    public function updateOrStoreInstallation(StoreOrUpdateInstallationRequest $request, $ci_id = null)
    {
        $validateData = $request->validated();
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
    }

    public function exportInstallation()
    {
        return Excel::download(new InstallationExport, 'Instalacion ' . date('m-Y') . '.xlsx');
    }

    // CicsaPurchaseOrderValidations

    public function indexOCValidation(Request $request)
    {
        if ($request->isMethod('get')) {
            $purchase_validations = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe')
                ->with('cicsa_purchase_order_validation.cicsa_purchase_order')
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
            ]);
        } elseif ($request->isMethod('post')) {

            $searchQuery = $request->searchQuery;
            $purchase_validations = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe')
                ->with('cicsa_purchase_order_validation.cicsa_purchase_order')
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
        try {
            $validationOc = CicsaPurchaseOrderValidation::find($cicsa_validation_order_id);
            $validationOc->update($validateData);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'Error processing file', 'error' => $e->getMessage()]);
        }
    }

    public function exportOCValidation()
    {
        return Excel::download(new OCValidationExport, 'Validación de OC ' . date('m-Y') . '.xlsx');
    }

    // CicsaServiceOrder
    public function indexServiceOrder(Request $request)
    {
        if ($request->isMethod('get')) {
            $service_orders = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe')
                ->with('cicsa_service_order.cicsa_purchase_order')
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
            ]);
        } elseif ($request->isMethod('post')) {
            $searchQuery = $request->searchQuery;
            $service_orders = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe')
                ->with('cicsa_service_order.cicsa_purchase_order')
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
            'user_name' => 'required',
            'user_id' => 'required',
        ]);

        $serviceOrderOc = CicsaServiceOrder::find($cicsa_service_order_id);
        $serviceOrderOc->update($validateData);
    }

    public function exportServiceOrder()
    {
        return Excel::download(new ServiceOrderExport, 'Orden de Servicio ' . date('m-Y') . '.xlsx');
    }

    //CicsaChargeArea
    public function indexChargeArea(Request $request)
    {
        if ($request->isMethod('get')) {
            $charge_areas = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe')
                ->with('cicsa_charge_area.cicsa_purchase_order')
                // ->whereHas('cicsa_service_order',function($query){
                //     $query->where('service_order','Completado')
                //     ->where('estimate_sheet','Completado')
                //     ->where('purchase_order','Completado')
                //     ->where('pdf_invoice','Completado')
                //     ->where('zip_invoice','Completado');
                // })
                ->orderBy('assignation_date', 'desc')
                ->paginate(20);
            return Inertia::render('Cicsa/CicsaChargeArea', [
                'charge_area' => $charge_areas,
            ]);
        } elseif ($request->isMethod('post')) {
            $searchQuery = $request->searchQuery;
            $charge_areas = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe')
                ->with('cicsa_charge_area.cicsa_purchase_order')
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
            'transaction_number_current' => 'nullable',
            'checking_account_amount' => 'nullable|numeric',
            'deposit_date_bank' => 'nullable|date',
            'transaction_number_bank' => 'nullable',
            'amount_bank' => 'nullable|numeric',
            'amount' => 'required',
            'user_name' => 'required',
            'user_id' => 'required',
        ]);

        $chargeAreaOc = CicsaChargeArea::find($cicsa_charge_area_id);
        $chargeAreaOc->update($validateData);
    }

    public function getChargeAreaAccepted()
    {
        $charge_areas = CicsaChargeArea::with('cicsa_purchase_order', 'cicsa_assignation')
            ->whereNotNull('checking_account_amount')
            ->whereNotNull('amount_bank');
        $dd = $charge_areas->get();
        return Inertia::render('Cicsa/CicsaChargeAreasAccepted', [
            'charge_areas' => $charge_areas->paginate(20),
            'total_amount' => $dd->sum('amount'),
            'total_checking_account_amount' => $dd->sum('checking_account_amount'),
            'total_amount_bank' => $dd->sum('amount_bank')
        ]);
    }

    public function exportChargeArea()
    {
        return Excel::download(new ChargeAreaExport, 'Cobranza ' . date('m-Y') . '.xlsx');
    }

    public function exportMaterialsSummary($ca_id)
    {
        return Excel::download(new MaterialsSummary($ca_id), 'ResumenMateriales.xlsx');
    }
}
