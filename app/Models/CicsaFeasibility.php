<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CicsaFeasibility extends Model
{
    use HasFactory;

    protected $table = 'cicsa_feasibilities';

    protected $fillable = [
        'feasibility_date',
        'report',
        'user_name',
        'user_id',
        'cicsa_assignation_id'
    ];

    public function user ()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cicsa_assignation ()
    {
        return $this->belongsTo(CicsaAssignation::class, 'cicsa_assignation_id');
    }

    public function cicsa_feasibility_materials ()
    {
        return $this->hasMany(CicsaFeasibilityMaterial::class, 'cicsa_feasibility_id');
    }


    public function getTotalMaterialsAttribute () {
        $total_materials=[];
        $list = $this->cicsa_feasibility_materials()->get();
        foreach($list as $item){
            $name = $item->name;
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
        return $total_materials;
    }
}
