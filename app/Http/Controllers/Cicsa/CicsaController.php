<?php

namespace App\Http\Controllers\Cicsa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cicsa\StoreOrUpdateAssigantionRequest;
use App\Http\Requests\Cicsa\StoreOrUpdateFeasibilitiesRequest;
use App\Http\Requests\Cicsa\StoreOrUpdateMaterialRequest;
use App\Http\Requests\Cicsa\StoreOrUpdatePurchaseOrderRequest;
use App\Models\CicsaAssignation;
use App\Models\CicsaFeasibility;
use App\Models\CicsaFeasibilityMaterial;
use App\Models\CicsaMaterial;
use App\Models\CicsaPurchaseOrder;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Mockery\Undefined;

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
        if ($cicsa_assignation_id) {
            foreach ($request->cicsa_feasibility_materials as $material) {
                if (!isset($material['id'])) {
                    $material['cicsa_feasibility_id'] = $cicsaFeasibilityId;
                    CicsaFeasibilityMaterial::create($material);
                }
            }
        } else {
            foreach ($request->cicsa_feasibility_materials as $material) {
                $material['cicsa_feasibility_id'] = $cicsaFeasibilityId;
                CicsaFeasibilityMaterial::create($material);
            }
        }
    }

    public function indexMaterial()
    {
        $material = CicsaAssignation::select('id', 'project_name')
            ->with('cicsa_feasibility.cicsa_feasibility_materials','cicsa_materials')
            ->paginate();
        return Inertia::render('Cicsa/CicsaMaterial', [
            'materials' => $material
        ]);
    }

    public function updateOrStoreMaterial(StoreOrUpdateMaterialRequest $request, $cicsa_assignation_id = null)
    {   
        $validateData = $request->validated();
        CicsaMaterial::updateOrCreate(
            ['cicsa_assignation_id' => $cicsa_assignation_id],
            $validateData
        );
    }

    public function indexPurchaseOrder()
    {
        $purchase_order = CicsaAssignation::select('id', 'project_name')
            ->with('cicsa_purchase_order')
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
}
