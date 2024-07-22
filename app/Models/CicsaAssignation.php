<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class CicsaAssignation extends Model
{
    use HasFactory;

    protected $table = 'cicsa_assignations';

    protected $fillable = [
        'assignation_date',
        'project_name',
        'customer',
        'project_code',
        'cpe',
        'project_deadline',
        'user_name',
        'user_id'
    ];

    protected $appends = [
        'total_materials',
        'cicsa_project_status',
        'cicsa_charge_status',
    ];

    public function user ()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cicsa_feasibility ()
    {
        return $this->hasOne(CicsaFeasibility::class, 'cicsa_assignation_id');
    }

    public function cicsa_installation ()
    {
        return $this->hasOne(CicsaInstallation::class, 'cicsa_assignation_id');
    }

    public function cicsa_materials ()
    {
        return $this->hasMany(CicsaMaterial::class, 'cicsa_assignation_id');
    }

    public function cicsa_purchase_order ()
    {
        return $this->hasOne(CicsaPurchaseOrder::class, 'cicsa_assignation_id');
    }

    public function cicsa_purchase_order_validation ()
    {
        return $this->hasOne(CicsaPurchaseOrderValidation::class, 'cicsa_assignation_id');
    }

    public function cicsa_service_order ()
    {
        return $this->hasOne(CicsaServiceOrder::class, 'cicsa_assignation_id');
    }

    public function cicsa_charge_area ()
    {
        return $this->hasOne(CicsaChargeArea::class, 'cicsa_assignation_id');
    }





    public function checkAssignation() {
        foreach ($this->fillable as $field) {
            if (is_null($this->$field)) {
                return false;
            }
        }
        return true;
    }
    public function checkFeasibility() {
        $feasibility = $this->cicsa_feasibility()->first();
        if (!$feasibility) {return false;}
        $fieldsToCheck = ['feasibility_date', 'report'];
        foreach ($fieldsToCheck as $field) {
            if (is_null($feasibility->$field)) {return false;}
        }
        if ($feasibility->report !== 'Completado') {return false;}
        if ($feasibility->cicsa_feasibility_materials()->count() === 0) {
            return false;
        }
        return true;
    }
    public function checkMaterials() {
        $materials = $this->cicsa_materials()->first();
        if (!$materials) {return false;}
        $fieldsToCheck = ['pick_date',
        'guide_number',
        'received_materials',];
        foreach ($fieldsToCheck as $field) {
            if (is_null($materials->$field)) {return false;}
        }
        if ($materials->cicsa_material_items()->count() === 0) {
            return false;
        }
        return true;
    }

    public function checkInstallation() {
        $installation = $this->cicsa_installation()->first();
        if (!$installation) {return false;}
        $fieldsToCheck = ['pext_date',
        'pint_date',
        'conformity',
        'report',
        'shipping_report_date',];
        foreach ($fieldsToCheck as $field) {
            if (is_null($installation->$field)) {return false;}
        }
        if ($installation->conformity !== 'Completado'
            || $installation->report !== 'Completado'
        ) {return false;}
        if ($installation->cicsa_installation_materials()->count() === 0) {
            return false;
        }
        return true;
    }


    public function checkPSP(){
        $feasibility = $this->cicsa_feasibility()->first();
        if ($feasibility) {return true;}
        $materials = $this->cicsa_materials()->first();
        if ($materials) {return true;}
        $installation = $this->cicsa_installation()->first();
        if ($installation) {return true;}
        return false;
    }


    public function getCicsaProjectStatusAttribute () {
        if ( $this->getCicsaChargeStatusAttribute() === 'Completado'
                || ( $this->checkAssignation()
                    && $this->checkFeasibility()
                    && $this->checkMaterials()
                    && $this->checkInstallation()
            )
        ) {
            return 'Completado';
        }

        if ($this->checkPSP() || $this->checkAssignation()) {
            return 'En Proceso';
        }
        if (!$this->checkAssignation()) {
            return 'Pendiente';
        }
    }



    public function checkPurchaseOrder() {
        $purchaseOrder = $this->cicsa_purchase_order()->first();
        if (!$purchaseOrder) {return false;}
        $fieldsToCheck = ['oc_date',
        'oc_number',
        'master_format',
        'item3456',
        'budget',];
        foreach ($fieldsToCheck as $field) {
            if (is_null($purchaseOrder->$field)) {return false;}
        }
        return true;
    }

    public function checkValidationOrder() {
        $validationOrder = $this->cicsa_purchase_order_validation()->first();
        if (!$validationOrder) {return false;}
        $fieldsToCheck = ['validation_date',
        'materials_control',
        'supervisor',
        'warehouse',
        'boss',
        'liquidator',
        'superintendent',];
        foreach ($fieldsToCheck as $field) {
            if (is_null($validationOrder->$field)) {return false;}
        }
        if ($validationOrder->materials_control !== 'Completado'
            || $validationOrder->supervisor !== 'Completado'
            || $validationOrder->warehouse !== 'Completado'
            || $validationOrder->boss !== 'Completado'
            || $validationOrder->liquidator !== 'Completado'
            || $validationOrder->superintendent !== 'Completado'
        ) {return false;}
        return true;
    }

    public function checkServiceOrder() {
        $serviceOrder = $this->cicsa_service_order()->first();
        if (!$serviceOrder) {return false;}
        $fieldsToCheck = ['service_order_date',
        'service_order',
        'estimate_sheet',
        'purchase_order',
        'pdf_invoice',
        'zip_invoice',];
        foreach ($fieldsToCheck as $field) {
            if (is_null($serviceOrder->$field)) {return false;}
        }
        if ($serviceOrder->estimate_sheet !== 'Completado'
            || $serviceOrder->purchase_order !== 'Completado'
            || $serviceOrder->pdf_invoice !== 'Completado'
            || $serviceOrder->zip_invoice !== 'Completado'
        ) {return false;}
        return true;
    }

    public function checkCicsaChargeArea() {
        $chargeArea = $this->cicsa_charge_area()->first();
        if (!$chargeArea) {return false;}
        $fieldsToCheck = ['invoice_number',
        'invoice_date',
        'payment_date',
        'deposit_date',
        'amount',];
        foreach ($fieldsToCheck as $field) {
            if (is_null($chargeArea->$field)) {return false;}
        }
        return true;
    }

    public function checkCSP(){
        $purchaseOrder = $this->cicsa_purchase_order()->first();
        if ($purchaseOrder) {return true;}
        $validationOrder = $this->cicsa_purchase_order_validation()->first();
        if ($validationOrder) {return true;}
        $serviceOrder = $this->cicsa_service_order()->first();
        if ($serviceOrder) {return true;}
        $chargeArea = $this->cicsa_charge_area()->first();
        if ($chargeArea) {return true;}
        return false;
    }

    public function getCicsaChargeStatusAttribute () {
        if (
            $this->checkCicsaChargeArea()
            // && $this->checkValidationOrder()
            // && $this->checkServiceOrder()
            // && $this->checkPurchaseOrder()
        ) {
            return 'Completado';
        }
        if ($this->checkCSP()) {
            return 'En Proceso';
        }
        if (!$this->checkPurchaseOrder()) {
            return 'Pendiente';
        }  
    }


    public function getTotalMaterialsAttribute () {
        $total_materials=[];
        $guides = $this->cicsa_materials()->with('cicsa_material_items')->get();
        foreach($guides as $guide){
            $list = $guide->cicsa_material_items;
            foreach($list as $item){
                $name = $item->name;
                $key = array_search($name, array_column($total_materials, 'name'));
                if($key !== false){
                    $newQuantity = $total_materials[$key]["quantity"] + $item->quantity;
                    $newGuideNumber = $total_materials[$key]["guide_number"].', '.$guide->guide_number;
                    $total_materials[$key]["quantity"] = $newQuantity;
                    $total_materials[$key]["guide_number"] = $newGuideNumber;
                } else {
                    array_push($total_materials,[
                        'name'=> $item->name,
                        'unit'=> $item->unit,
                        'guide_number' => $guide->guide_number,
                        'quantity'=> $item->quantity,
                    ]);
                }
            }
        }
        return $total_materials;
    }
}
