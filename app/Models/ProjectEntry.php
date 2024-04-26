<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectEntry extends Model
{
    use HasFactory;
    protected $table = 'project_entries';
    protected $fillable = [
        'project_id',
        'entry_id',
        'special_inventory_id',
        'quantity',
        'observation',
        'unitary_price',
        'state'
    ];

    protected $appends = [
        'outputs_state',
        'current_output_quantity',
        'remaining_quantity',
        'total_price'
    ];

    //Relations
    public function project ()
    {
        return $this->belongsTo(Project::class,'project_id');
    } 

    public function special_inventory (){
        return $this->belongsTo(SpecialInventory::class,'special_inventory_id');
    }

    public function entry (){
        return $this->belongsTo(Entry::class,'entry_id');
    }

    public function project_entry_outputs ()
    {
        return $this->hasMany(ProjectEntryOutput::class);
    }

    public function project_entry_liquidation ()
    {
        return $this->hasOne(ProjectEntryLiquidation::class);
    }

    public function dispath ()
    {
        return $this->hasMany(Dispatch::class);
    }


    public function getCurrentOutputQuantityAttribute (){
        return $this->project_entry_outputs()
                            ->get()
                            ->sum(function($item) {
                                return $item->quantity;
                            })?? 0;
    }
    public function getRemainingQuantityAttribute (){
        return $this->quantity -
                $this->getCurrentOutputQuantityAttribute();
    }
    
    public function getOutputsStateAttribute (){
        //modificar
        return $this->getCurrentOutputQuantityAttribute() 
                === $this->quantity ;
    }

    public function getTotalPriceAttribute()
    {
        // if ($this->project_entry_liquidation){
        //     return $this->project_entry_liquidation->liquidated_quantity * $this->unitary_price ? $this->unitary_price : 0;
        // } else{
        //     return $this->quantity * $this->unitary_price ? $this->unitary_price : 0;
        // }
        return $this->quantity * ($this->unitary_price ? $this->unitary_price : 0);
    }

}
