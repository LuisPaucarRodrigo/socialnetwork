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
use Maatwebsite\Excel\HeadingRowImport;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CicsaController extends Controller
{
    public function index()
    {
        $projects = CicsaAssignation::orderBy('assignation_date', 'desc')
            ->with(
                'cicsa_feasibility.cicsa_feasibility_materials',
                'cicsa_materials.cicsa_material_items',
                'cicsa_installation.cicsa_installation_materials',
                'cicsa_purchase_order',
                'cicsa_purchase_order_validation',
                'cicsa_service_order',
                'cicsa_charge_area'
            )
            ->paginate(20);
        return Inertia::render('Cicsa/CicsaIndex', [
            'projects' => $projects
        ]);
    }

    public function destroy($ca_id)
    {
        CicsaAssignation::findOrFail($ca_id)->delete();
        return redirect()->back();
    }

    public function indexAssignation()
    {
        $assignation = CicsaAssignation::orderBy('assignation_date', 'desc')->paginate();
        return Inertia::render('Cicsa/CicsaAssignation', [
            'assignation' => $assignation
        ]);
    }

    public function updateOrStoreAssignation(StoreOrUpdateAssigantionRequest $request, $cicsa_assignation_id = null)
    {
        $validateData = $request->validated();
        CicsaAssignation::updateOrCreate(
            ['id' => $cicsa_assignation_id],
            $validateData
        );
    }

    public function indexFeasibilities()
    {
        $feasibility = CicsaAssignation::select('id', 'project_name')
            ->with('cicsa_feasibility.cicsa_feasibility_materials')
            ->orderBy('assignation_date', 'desc')
            ->paginate();
        return Inertia::render('Cicsa/CicsaFeasibility', [
            'feasibility' => $feasibility
        ]);
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

    public function indexMaterial()
    {
        $material = CicsaAssignation::select('id', 'project_name')
            ->with('cicsa_feasibility.cicsa_feasibility_materials', 'cicsa_materials.cicsa_material_items')
            ->orderBy('assignation_date', 'desc')
            ->paginate();
        return Inertia::render('Cicsa/CicsaMaterial', [
            'materials' => $material
        ]);
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

        if ($request->hasFile('document')) {
            $file = $request->file('document');

            $spreadsheet = IOFactory::load($file);

            // $data = Excel::toArray(new HeadingRowImport, $file);
            // $processedData = [];
            // foreach ($data[0] as $row) {
            //     $processedData[] = [
            //         'nombre' => $row['Nombre'],
            //         'unidad' => $row['Unidad'],
            //         'cantidad' => $row['Cantidad'],
            //     ];
            // }

            return response()->json([
                'data' => $spreadsheet,
            ]);
        }

        return response()->json([
            'message' => 'No file uploaded'
        ], 400);
    }

    public function indexPurchaseOrder()
    {
        $purchase_order = CicsaAssignation::select('id', 'project_name')
            ->with('cicsa_purchase_order')
            ->orderBy('assignation_date', 'desc')
            ->paginate();
        return Inertia::render('Cicsa/CicsaPurchaseOrder', [
            'purchaseOrder' => $purchase_order
        ]);
    }

    public function updateOrStorePurchaseOrder(StoreOrUpdatePurchaseOrderRequest $request, $cicsa_assignation_id = null)
    {
        $validateData = $request->validated();
        CicsaPurchaseOrder::updateOrCreate(
            ['cicsa_assignation_id' => $cicsa_assignation_id],
            $validateData
        );
    }


    public function indexInstallation()
    {
        $installations = CicsaAssignation::select('id', 'project_name')
            ->with(
                'cicsa_installation.cicsa_installation_materials',
                'cicsa_installation.user'
            )
            ->orderBy('assignation_date', 'desc')
            ->paginate();
        return Inertia::render('Cicsa/CicsaInstallation', [
            'installations' => $installations
        ]);
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

    public function indexOCValidation()
    {
        $purchase_validations = CicsaAssignation::select('id', 'project_name')
            ->with('cicsa_purchase_order_validation')
            ->orderBy('assignation_date', 'desc')
            ->paginate(10);
        return Inertia::render('Cicsa/CicsaPurchaseOrderValidation', [
            'purchase_validations' => $purchase_validations,
        ]);
    }

    public function storeOCValidation(Request $request, $cicsa_assignation_id = null)
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

        CicsaPurchaseOrderValidation::updateOrCreate(
            ['cicsa_assignation_id' => $cicsa_assignation_id],
            $validateData
        );
    }

    public function updateOCValidation(Request $request, CicsaPurchaseOrderValidation $cicsa_validation_id)
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

        $cicsa_validation_id->update(
            $validateData
        );
    }

    // CicsaServiceOrder

    public function indexServiceOrder()
    {
        $service_orders = CicsaAssignation::select('id', 'project_name')
            ->with('cicsa_service_order')
            ->orderBy('assignation_date', 'desc')
            ->paginate(10);
        return Inertia::render('Cicsa/CicsaServiceOrder', [
            'service_orders' => $service_orders,
        ]);
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
    }

    //CicsaChargeArea

    public function indexChargeArea()
    {
        $charge_areas = CicsaAssignation::select('id', 'project_name')
            ->with('cicsa_charge_area')
            ->orderBy('assignation_date', 'desc')
            ->paginate(10);
        return Inertia::render('Cicsa/CicsaChargeArea', [
            'charge_areas' => $charge_areas,
        ]);
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
    }
}
