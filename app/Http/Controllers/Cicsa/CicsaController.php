<?php

namespace App\Http\Controllers\Cicsa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cicsa\StoreOrUpdateAssigantionRequest;
use App\Http\Requests\Cicsa\StoreOrUpdateFeasibilitiesRequest;
use App\Models\CicsaAssignation;
use App\Models\CicsaChargeArea;
use App\Models\CicsaFeasibility;
use App\Models\CicsaFeasibilityMaterial;
use App\Models\CicsaMaterial;
use App\Models\CicsaPurchaseOrder;
use App\Models\CicsaPurchaseOrderValidation;
use App\Models\CicsaServiceOrder;
use Inertia\Inertia;
use Illuminate\Http\Request;

class CicsaController extends Controller
{
    public function index()
    {
        return Inertia::render('Cicsa/CicsaIndex');
    }

    public function indexAssignation()
    {
        $assignation = CicsaAssignation::paginate();
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
            ->paginate();
        return Inertia::render('Cicsa/CicsaFeasibility', [
            'feasibility' => $feasibility
        ]);
    }

    public function updateOrStoreFeasibilities(StoreOrUpdateFeasibilitiesRequest $request, $cicsa_assignation_id = null)
    {   

        $validateData = $request->validated();
        $cicsaFeasibility =  CicsaFeasibility::updateOrCreate(
            ['cicsa_assignation_id' => $cicsa_assignation_id],
            $validateData
        );
        $cicsaFeasibilityId = $cicsaFeasibility->id;
        foreach ($request->material_feasibility as $material) {
            $material['cicsa_feasibility_id'] = $cicsaFeasibilityId;
            CicsaFeasibilityMaterial::create($material);
        }
    }

    public function indexMaterial()
    {
        $materials = CicsaMaterial::paginate();
        return Inertia::render('Cicsa/CicsaMaterial', [
            'materiales' => $materials
        ]);
    }

    public function indexPurchaseOrder()
    {
        $purchase_order = CicsaPurchaseOrder::paginate();
        return Inertia::render('Cicsa/CicsaPurchaseOrder', [
            'purchase_order' => $purchase_order
        ]);
    }

    // CicsaPurchaseOrderValidations

    public function indexOCValidation ()
    {
        $purchase_validations = CicsaAssignation::select('id', 'project_name')
            ->with('cicsa_purchase_order_validation')
            ->paginate(10);
        return Inertia::render('Cicsa/CicsaPurchaseOrderValidation', [
            'purchase_validations' => $purchase_validations,
        ]);
    }

    public function storeOCValidation (Request $request, $cicsa_assignation_id = null)
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

    public function updateOCValidation (Request $request, CicsaPurchaseOrderValidation $cicsa_validation_id)
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

    public function indexServiceOrder ()
    {
        $service_orders = CicsaAssignation::select('id', 'project_name')
            ->with('cicsa_service_order')
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

    public function indexChargeArea ()
    {
        $charge_areas = CicsaAssignation::select('id', 'project_name')
            ->with('cicsa_charge_area')
            ->paginate(10);
        return Inertia::render('Cicsa/CicsaChargeArea', [
            'charge_areas' => $charge_areas,
        ]);
    }

    public function storeChargeArea(Request $request, $cicsa_assignation_id = null)
    {
        $validateData = $request->validate([
            'invoice_number' => 'required',
            'invoice_date' => 'required',
            'payment_date' => 'required',
            'deposit_date' => 'nullable',
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
            'invoice_number' => 'required',
            'invoice_date' => 'required',
            'payment_date' => 'required',
            'deposit_date' => 'nullable',
            'amount' => 'required',
            'user_name' => 'required',
            'user_id' => 'required',
        ]);

        $cicsa_charge_area->update($validateData);
    }
}
