<?php

namespace App\Http\Controllers\Cicsa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cicsa\StoreOrUpdateAssigantionRequest;
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
use Inertia\Inertia;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Pagination\LengthAwarePaginator;

class CicsaController extends Controller
{
    public function index()
    {
        $projects = CicsaAssignation::join('cicsa_purchase_orders', 'cicsa_assignations.id', '=', 'cicsa_purchase_orders.cicsa_assignation_id')

            ->where(function ($query) {
                // Registros que no tienen la relación cicsa_charge_area
                $query->whereDoesntHave('cicsa_charge_area')
                    ->orWhere(function ($query) {
                        // Registros que tienen la relación cicsa_charge_area pero al menos uno de los campos invoice_number, invoice_date o amount es NULL
                        $query->whereHas('cicsa_charge_area', function ($subQuery) {
                            $subQuery->where(function ($subQuery) {
                                $subQuery->whereNull('invoice_number')
                                    ->orWhereNull('invoice_date')
                                    ->orWhereNull('amount');
                            })
                                ->whereNull('deposit_date'); // Asegura que deposit_date sea NULL
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
            // ->orderBy('cicsa_purchase_order.oc_number', 'desc')
            // ->leftJoin('cicsa_purchase_orders', 'cicsa_assignations.id', '=', 'cicsa_purchase_orders.cicsa_assignation_id')

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
                            ->whereNull('deposit_date'); // Asegura que deposit_date sea NULL
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
        // ->leftJoin('cicsa_purchase_orders', 'cicsa_assignations.id', '=', 'cicsa_purchase_orders.cicsa_assignation_id')
        // ->orderBy('cicsa_purchase_orders.oc_number', 'desc');

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

    public function chargeCicsa()
    {
        $charge_areas = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe')
            ->with('cicsa_charge_area')
            ->whereHas('cicsa_charge_area', function ($query) {
                $query->whereNotNull('invoice_number')
                    ->whereNotNull('invoice_date')
                    ->whereNotNull('amount');
            })
            ->paginate(20);
        return Inertia::render('Cicsa/CicsaCollect', [
            'charge_areas' => $charge_areas,
        ]);
    }

    public function indexAssignation(Request $request)
    {
        if ($request->isMethod('get')) {
            $assignation = CicsaAssignation::paginate(20);
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
        CicsaAssignation::updateOrCreate(
            ['id' => $cicsa_assignation_id],
            $validateData
        );
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
        return redirect()->back();
    }

    public function updateMaterial(StoreOrUpdateMaterialRequest $request, $cicsa_assignation_id = null)
    {
        $validateData = $request->validated();
        $cicsaMaterial = CicsaMaterial::updateOrCreate(
            ['cicsa_assignation_id' => $cicsa_assignation_id],
            $validateData
        );
        if ($cicsaMaterial->cicsa_material_items) {
            CicsaMaterialsItem::where('cicsa_material_id', $cicsaMaterial->id)->delete();
        }
        foreach ($request->cicsa_material_items as $item) {
            $item['cicsa_material_id'] = $cicsaMaterial->id;
            CicsaMaterialsItem::create($item);
        }
        return redirect()->back();
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
                ->orderBy('assignation_date', 'desc')
                ->paginate(20);
            return Inertia::render('Cicsa/CicsaPurchaseOrder', [
                'purchaseOrder' => $purchase_order
            ]);
        } elseif ($request->isMethod('post')) {
            $searchQuery = $request->searchQuery;
            $purchase_order = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe')
                ->with('cicsa_purchase_order')
                ->orWhere('project_name', 'like', "%$searchQuery%")
                ->orWhere('project_code', 'like', "%$searchQuery%")
                ->orWhere('cpe', 'like', "%$searchQuery%")
                ->orWhere(function ($query) use ($searchQuery) {
                    $query
                        ->whereHas('cicsa_purchase_order', function ($query) use ($searchQuery) {
                            $query->where('oc_number', 'like', "%$searchQuery%");
                        });
                })
                ->get();
            return response()->json([
                'purchaseOrder' => $purchase_order
            ]);
        }
    }

    public function updateOrStorePurchaseOrder(StoreOrUpdatePurchaseOrderRequest $request, $cicsa_assignation_id = null)
    {
        $validateData = $request->validated();
        CicsaPurchaseOrder::updateOrCreate(
            ['cicsa_assignation_id' => $cicsa_assignation_id],
            $validateData
        );
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
                ->paginate(20);
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
        return redirect()->back();
    }


    // CicsaPurchaseOrderValidations

    public function indexOCValidation(Request $request)
    {
        if ($request->isMethod('get')) {
            $purchase_validations = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe')
                ->with('cicsa_purchase_order_validation', 'cicsa_purchase_order')
                ->orderBy('assignation_date', 'desc')
                ->paginate(20);
            return Inertia::render('Cicsa/CicsaPurchaseOrderValidation', [
                'purchase_validation' => $purchase_validations,
            ]);
        } elseif ($request->isMethod('post')) {

            $searchQuery = $request->searchQuery;
            $purchase_validations = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe')
                ->with('cicsa_purchase_order_validation', 'cicsa_purchase_order')
                ->orWhere('project_name', 'like', "%$searchQuery%")
                ->orWhere('project_code', 'like', "%$searchQuery%")
                ->orWhere('cpe', 'like', "%$searchQuery%")
                ->orWhere(function ($query) use ($searchQuery) {
                    $query->whereHas('cicsa_purchase_order', function ($query) use ($searchQuery) {
                        $query->where('oc_number', 'like', "%$searchQuery%");
                    });
                })
                ->get();
            return response()->json([
                'purchase_validation' => $purchase_validations,
            ]);
        }
    }

    public function storeOrUpdateOCValidation(Request $request, $cicsa_assignation_id = null)
    {
        $validateData = $request->validate([
            'validation_date' => 'required',
            'materials_control' => 'required',
            'supervisor' => 'required',
            'warehouse' => 'required',
            'boss' => 'required',
            'liquidator' => 'required',
            'superintendent' => 'required',
            'user_name' => 'required',
            'user_id' => 'required',
        ]);
        try {
            CicsaPurchaseOrderValidation::updateOrCreate(
                ['cicsa_assignation_id' => $cicsa_assignation_id],
                $validateData
            );
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'Error processing file', 'error' => $e->getMessage()]);
        }
    }

    // public function updateOCValidation(Request $request, CicsaPurchaseOrderValidation $cicsa_validation_id)
    // {
    //     $validateData = $request->validate([
    //         'validation_date' => 'required',
    //         'materials_control' => 'required',
    //         'supervisor' => 'required',
    //         'warehouse' => 'required',
    //         'boss' => 'required',
    //         'liquidator' => 'required',
    //         'superintendent' => 'required',
    //         'user_name' => 'required',
    //         'user_id' => 'required',
    //     ]);

    //     $cicsa_validation_id->update(
    //         $validateData
    //     );
    //     return redirect()->back();
    // }

    // CicsaServiceOrder

    public function indexServiceOrder(Request $request)
    {
        if ($request->isMethod('get')) {
            $service_orders = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe')
                ->with('cicsa_service_order')
                ->orderBy('assignation_date', 'desc')
                ->paginate(20);
            return Inertia::render('Cicsa/CicsaServiceOrder', [
                'service_order' => $service_orders,
            ]);
        } elseif ($request->isMethod('post')) {
            $service_orders = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe')
                ->with('cicsa_service_order')
                ->orWhere('project_name', 'like', "%$request->searchQuery%")
                ->orWhere('project_code', 'like', "%$request->searchQuery%")
                ->orWhere('cpe', 'like', "%$request->searchQuery%")
                ->get();
            return response()->json([
                'service_order' => $service_orders,
            ]);
        }
    }

    public function storeServiceOrder(Request $request, $cicsa_assignation_id = null)
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

        CicsaServiceOrder::updateOrCreate(
            ['cicsa_assignation_id' => $cicsa_assignation_id],
            $validateData
        );
        return redirect()->back();
    }

    public function updateServiceOrder(Request $request, CicsaServiceOrder $cicsa_service_order_id)
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

        $cicsa_service_order_id->update(
            $validateData
        );
        return redirect()->back();
    }

    //CicsaChargeArea

    public function indexChargeArea(Request $request)
    {
        if ($request->isMethod('get')) {
            $charge_areas = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe')
                ->with('cicsa_charge_area')
                ->orderBy('assignation_date', 'desc')
                ->paginate(20);
            return Inertia::render('Cicsa/CicsaChargeArea', [
                'charge_area' => $charge_areas,
            ]);
        } elseif ($request->isMethod('post')) {
            $charge_areas = CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe')
                ->with('cicsa_charge_area')
                ->orWhere('project_name', 'like', "%$request->searchQuery%")
                ->orWhere('project_code', 'like', "%$request->searchQuery%")
                ->orWhere('cpe', 'like', "%$request->searchQuery%")
                ->get();
            return response()->json([
                'charge_area' => $charge_areas,
            ]);
        }
    }

    public function storeChargeArea(Request $request, $cicsa_assignation_id = null)
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
            'amount' => 'required',
            'user_name' => 'required',
            'user_id' => 'required',
        ]);

        CicsaChargeArea::updateOrCreate(
            ['cicsa_assignation_id' => $cicsa_assignation_id],
            $validateData
        );
        return redirect()->back();
    }

    public function updateChargeArea(Request $request, CicsaChargeArea $cicsa_charge_area)
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
            'amount' => 'required',
            'user_name' => 'required',
            'user_id' => 'required',
        ]);

        $cicsa_charge_area->update($validateData);
        return redirect()->back();
    }

    public function getChargeAreaAccepted()
    {
        // Obtener y filtrar las asignaciones
        $charge_areas = CicsaAssignation::with('cicsa_charge_area')->get()->filter(function ($assignation) {
            return $assignation->cicsa_charge_area !== null && $assignation->cicsa_charge_area->state === 'Pagado';
        });

        // Paginar manualmente la colección filtrada
        $perPage = 10; // Número de elementos por página
        $page = request()->input('page', 1); // Obtener la página actual
        $total = $charge_areas->count(); // Contar el total de elementos

        $currentPageItems = $charge_areas->slice(($page - 1) * $perPage, $perPage)->values(); // Obtener los elementos para la página actual

        $paginatedChargeAreas = new LengthAwarePaginator($currentPageItems, $total, $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);

        return Inertia::render('Cicsa/CicsaChargeAreasAccepted', [
            'charge_areas' => $paginatedChargeAreas
        ]);
    }
}
