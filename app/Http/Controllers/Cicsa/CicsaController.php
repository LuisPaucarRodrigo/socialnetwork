<?php

namespace App\Http\Controllers\Cicsa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cicsa\StoreOrUpdateAssigantionRequest;
use App\Http\Requests\Cicsa\StoreOrUpdateFeasibilitiesRequest;
use App\Models\CicsaAssignation;
use App\Models\CicsaFeasibility;
use App\Models\CicsaFeasibilityMaterial;
use App\Models\CicsaMaterial;
use App\Models\CicsaPurchaseOrder;
use Inertia\Inertia;

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











    public function indexInstallation()
    {
        $installations = CicsaAssignation::select('id', 'project_name')
            ->with(
                'cicsa_installation.cicsa_installation_materials',
                'cicsa_installation.user'
                )
            ->orderBy('updated_at', 'desc')
            ->paginate();
        return Inertia::render('Cicsa/CicsaInstallation', [
            'installations' => $installations
        ]);
    }
















}
