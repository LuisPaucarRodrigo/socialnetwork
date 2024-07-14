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
        return $this->hasOne(CicsaMaterial::class, 'cicsa_assignation_id');
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
        $feasibility = $this->cicsa_materials()->first();
        if (!$feasibility) {return false;}
        $fieldsToCheck = ['pick_date',
        'guide_number',
        'received_materials',];
        foreach ($fieldsToCheck as $field) {
            if (is_null($feasibility->$field)) {return false;}
        }
        return true;
    }

    public function checkInstallation() {
        $feasibility = $this->cicsa_installation()->first();
        if (!$feasibility) {return false;}
        $fieldsToCheck = ['pext_date',
        'pint_date',
        'conformity',
        'report',
        'shipping_report_date',];
        foreach ($fieldsToCheck as $field) {
            if (is_null($feasibility->$field)) {return false;}
        }
        if ($feasibility->conformity !== 'Completado'
            && $feasibility->report !== 'Completado'
        ) {return false;}
        if ($feasibility->cicsa_installation_materials()->count() === 0) {
            return false;
        }
        return true;
    }


    public function getCicsaProjectStatusAttribute () {
        return [
            'assignation' => $this->checkAssignation(),
            'feasibility' => $this->checkFeasibility(),
            'material' => $this->checkMaterials(),
            'installation' => $this->checkInstallation(),
        ];
    }



    public function getTotalMaterialsAttribute () {
        $total_materials=[];
        $guides = $this->cicsa_feasibility()->with('cicsa_feasibility_materials')->get();
        foreach($guides as $guide){
            $list = $guide->cicsa_feasibility_materials;
            foreach($list as $item){
                $name = $item->name;
                Log::info($name);
                $key = array_search($name, array_column($total_materials, 'name'));
                if($key !== false){
                    $newQuantity = $total_materials[$key]["quantity"] + $item->quantity;
                    $total_materials[$key]["quantity"] = $newQuantity;
                } else {
                    array_push($total_materials,[
                        'name'=> $item->name,
                        'unit'=> $item->unit,
                        'quantity'=> $item->quantity,
                    ]);
                }
            }
        }
        return $total_materials;
    }
}
