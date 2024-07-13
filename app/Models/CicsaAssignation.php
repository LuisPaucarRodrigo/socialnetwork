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
        'total_materials'
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

    public function getTotalMaterialsAttribute ()
    {
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
