<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialInventory extends Model
{
    use HasFactory;
    protected $fillable = [
        'purchase_product_id',
        'warehouse_id',
        'cpe',
        'referral_guide',
        'entry_date',
        'sub_warehouse',
        'quantity',
        'product_serial_number',
        'zone',
        'entry_observations',
    ];

    protected $appends = [
        'reserved_quantity',
        'quantity_available',
        'used_quantity'
    ];

    //Relations
    public function project_entry()
    {
        return $this->hasMany(ProjectEntry::class);
    }

    public function purchase_product()
    {
        return $this->belongsTo(Purchase_product::class, 'purchase_product_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function getReservedQuantityAttribute(){
        $quantityInProjects = $this->project_entry()
                                    ->get()
                                    ->filter(function($item){
                                        return !$item->outputs_state;
                                    })
                                    ->sum('quantity');
        return $quantityInProjects;
    }

    public function getUsedQuantityAttribute() {
        return $this->project_entry()
                    ->get()
                    ->filter(function($item){
                        return $item->outputs_state;
                    })
                    ->sum('quantity');
    }

    public function getQuantityAvailableAttribute() {
        return $this->quantity - $this->reserved_quantity - $this->used_quantity;
    }
}
