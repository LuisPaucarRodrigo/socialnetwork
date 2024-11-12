<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Psy\CodeCleaner\ReturnTypePass;

class CicsaAssignation extends Model
{
    use HasFactory;

    protected $table = 'cicsa_assignations';

    protected $fillable = [
        'assignation_date',
        'project_name',
        'cost_center',
        'customer',
        'project_code',
        'cpe',
        'zone',
        'manager',
        'user_name',
        'user_id'
    ];

    protected $appends = [
        'total_materials',
        'cicsa_project_status',
        'cicsa_administration_status',
        'cicsa_charge_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cicsa_feasibility()
    {
        return $this->hasOne(CicsaFeasibility::class, 'cicsa_assignation_id');
    }

    public function cicsa_installation()
    {
        return $this->hasOne(CicsaInstallation::class, 'cicsa_assignation_id');
    }

    public function cicsa_materials()
    {
        return $this->hasMany(CicsaMaterial::class, 'cicsa_assignation_id');
    }

    public function cicsa_purchase_order()
    {
        return $this->hasMany(CicsaPurchaseOrder::class, 'cicsa_assignation_id');
    }

    public function cicsa_purchase_order_validation()
    {
        return $this->hasMany(CicsaPurchaseOrderValidation::class, 'cicsa_assignation_id');
    }

    public function cicsa_service_order()
    {
        return $this->hasMany(CicsaServiceOrder::class, 'cicsa_assignation_id');
    }

    public function cicsa_charge_area()
    {
        return $this->hasMany(CicsaChargeArea::class, 'cicsa_assignation_id');
    }





    public function checkAssignation()
    {
        foreach ($this->fillable as $field) {
            if (is_null($this->$field)) {
                return false;
            }
        }
        return true;
    }

    public function checkFeasibility()
    {
        $feasibility = $this->cicsa_feasibility()->first();
        if (!$feasibility) {
            return false;
        }
        $fieldsToCheck = ['feasibility_date', 'report'];
        foreach ($fieldsToCheck as $field) {
            if (is_null($feasibility->$field)) {
                return false;
            }
        }
        if ($feasibility->report !== 'Completado') {
            return false;
        }
        // if ($feasibility->cicsa_feasibility_materials()->count() === 0) {
        //     return false;
        // }
        return true;
    }

    public function checkMaterials()
    {
        $materials = $this->cicsa_materials()->first();
        if (!$materials) {
            return false;
        }
        $fieldsToCheck = [
            'pick_date',
            'guide_number',
            // 'received_materials',
        ];
        foreach ($fieldsToCheck as $field) {
            if (is_null($materials->$field)) {
                return false;
            }
        }
        // if ($materials->cicsa_material_items()->count() === 0) {
        //     return false;
        // }
        return true;
    }

    public function checkInstallation()
    {
        $installation = $this->cicsa_installation()->first();
        if (!$installation) {
            return false;
        }
        $fieldsToCheck = [
            'pext_date',
            'pint_date',
            'conformity',
            'report',
            'shipping_report_date',
        ];
        foreach ($fieldsToCheck as $field) {
            if (is_null($installation->$field)) {
                return false;
            }
        }
        if (
            $installation->conformity !== 'Completado'
            || $installation->report !== 'Completado'
        ) {
            return false;
        }
        // if ($installation->cicsa_installation_materials()->count() === 0) {
        //     return false;
        // }
        return true;
    }


    public function checkPSP()
    {
        $feasibility = $this->cicsa_feasibility()->first();
        if ($feasibility) {
            return true;
        }
        $materials = $this->cicsa_materials()->first();
        if ($materials) {
            return true;
        }
        $installation = $this->cicsa_installation()->first();
        if ($installation) {
            return true;
        }
        return false;
    }


    public function getCicsaProjectStatusAttribute()
    {
        if (
            $this->getCicsaChargeStatusAttribute() === 'Completado'
            || ($this->checkAssignation()
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



    public function checkPurchaseOrder()
    {
        $purchaseOrders = $this->cicsa_purchase_order()->get();
        if ($purchaseOrders->isEmpty()) {
            return false;
        }
        $fieldsToCheck = [
            'oc_date',
            'oc_number',
            'master_format',
            'item3456',
            'budget',
            'document'
        ];
        foreach ($purchaseOrders as $purchaseOrder) {
            foreach ($fieldsToCheck as $field) {
                if (is_null($purchaseOrder->$field)) {
                    return false;
                }
            }
            if (
                $purchaseOrder->master_format !== 'Completado'
                || $purchaseOrder->item3456 !== 'Completado'
                || $purchaseOrder->budget !== 'Completado'
            ) {
                return false;
            }
        }
        return true;
    }

    public function checkValidationOrder()
    {
        $validationOrders = $this->cicsa_purchase_order_validation()->get();
        if ($validationOrders->isEmpty()) {
            return false;
        }
        $fieldsToCheck = [
            'validation_date',
            'materials_control',
            'file_validation',
            'supervisor',
            'warehouse',
            'boss',
            'liquidator',
            'superintendent'
        ];
        foreach ($validationOrders as $validationOrder) {
            foreach ($fieldsToCheck as $field) {
                if (is_null($validationOrder->$field)) {
                    return false;
                }
            }
            if (
                $validationOrder->materials_control !== 'Completado'
                || $validationOrder->file_validation !== 'Completado'
                || $validationOrder->supervisor !== 'Completado'
                || $validationOrder->warehouse !== 'Completado'
                || $validationOrder->boss !== 'Completado'
                || $validationOrder->liquidator !== 'Completado'
                || $validationOrder->superintendent !== 'Completado'
            ) {
                return false;
            }
        }



        return true;
    }

    public function checkServiceOrder()
    {
        $serviceOrders = $this->cicsa_service_order()->get();
        if ($serviceOrders->isEmpty()) {
            return false;
        }

        $fieldsToCheck = [
            'service_order_date',
            'service_order',
            'estimate_sheet',
            'purchase_order',
            'pdf_invoice',
            'zip_invoice',
            'document',
        ];

        foreach ($serviceOrders as $serviceOrder) {
            foreach ($fieldsToCheck as $field) {
                if (is_null($serviceOrder->$field)) {
                    return false;
                }
            }
            if (
                $serviceOrder->service_order !== 'Completado'
                || $serviceOrder->estimate_sheet !== 'Completado'
                || $serviceOrder->purchase_order !== 'Completado'
                || $serviceOrder->pdf_invoice !== 'Completado'
                || $serviceOrder->zip_invoice !== 'Completado'
            ) {
                return false;
            }
        }


        return true;
    }

    // public function checkCicsaChargeArea()
    // {

    //     $chargeAreas = $this->cicsa_charge_area()->get();
    //     if ($chargeAreas->isEmpty()) {
    //         return false;
    //     }
    //     $fieldsToCheck = [
    //         'invoice_number',
    //         'invoice_date',
    //         'credit_to',
    //         'amount',
    //         'deposit_date',
    //         'transaction_number_current',
    //         'checking_account_amount',
    //         'deposit_date_bank',
    //         'transaction_number_bank',
    //         'amount_bank',
    //         'document',
    //     ];
    //     foreach ($chargeAreas as $chargeArea) {
    //         foreach ($fieldsToCheck as $field) {
    //             if (is_null($chargeArea->$field)) {
    //                 return false;
    //             }
    //         }
    //     }
    //     return true;
    // }

    // public function checkCSP()
    // {
    //     $purchaseOrder = $this->cicsa_purchase_order()->first();
    //     if ($purchaseOrder) {
    //         return true;
    //     }
    //     $validationOrder = $this->cicsa_purchase_order_validation()->first();
    //     if ($validationOrder) {
    //         return true;
    //     }
    //     $serviceOrder = $this->cicsa_service_order()->first();
    //     if ($serviceOrder) {
    //         return true;
    //     }
    //     $chargeArea = $this->cicsa_charge_area()->first();
    //     if ($chargeArea) {
    //         return true;
    //     }
    //     return false;
    // }

    public function getCicsaAdministrationStatusAttribute()
    {
        if (
            $this->checkPurchaseOrder()
            && $this->checkValidationOrder()
            && $this->checkServiceOrder()
        ) {
            return 'Completado';
        } else {
            if (
                !$this->checkPurchaseOrder()
                && !$this->checkValidationOrder()
                && !$this->checkServiceOrder()
            ) {
                return 'Pendiente';
            } else {
                return 'En Proceso';
            }
        }
    }

    public function getCicsaChargeStatusAttribute()
    {
        $chargeAreas = $this->cicsa_charge_area()->get();
        $fieldsToCheck = [
            'invoice_number',
            'invoice_date',
            'credit_to',
            'amount',
            'deposit_date',
            'transaction_number_current',
            'checking_account_amount',
            'deposit_date_bank',
            'transaction_number_bank',
        ];

        foreach ($chargeAreas as $chargeArea) {
            if (is_null($chargeArea->amount)) {
                return 'Pendiente';
            } else {
                foreach ($fieldsToCheck as $field) {
                    if(is_null($chargeArea->$field)){
                        return 'En Proceso';
                    }
                }
            }
        }

        return'Completado';
    }


    public function getTotalMaterialsAttribute()
    {
        $total_materials = [];
        $guides = $this->cicsa_materials()->with('cicsa_material_items')->get();
        foreach ($guides as $guide) {
            $list = $guide->cicsa_material_items;
            foreach ($list as $item) {
                $name = $item->name;
                $key = array_search($name, array_column($total_materials, 'name'));
                if ($key !== false) {
                    $newQuantity = $total_materials[$key]["quantity"] + $item->quantity;
                    $newGuideNumber = $total_materials[$key]["guide_number"] . ', ' . $guide->guide_number;
                    $total_materials[$key]["quantity"] = $newQuantity;
                    $total_materials[$key]["guide_number"] = $newGuideNumber;
                } else {
                    array_push($total_materials, [
                        'name' => $item->name,
                        'unit' => $item->unit,
                        'type' => $item->type,
                        'guide_number' => $guide->guide_number,
                        'quantity' => $item->quantity,
                    ]);
                }
            }
        }
        return $total_materials;
    }
}
