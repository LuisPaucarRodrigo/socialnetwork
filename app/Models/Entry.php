<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    use HasFactory;
    protected $fillable = [
        'inventory_id',
        'type',
        'quantity',
        'unitary_price',
        'entry_date',
        'observations',
    ];

    protected $appends = [
        'used_quantity',
        'currency',
        'reserved_quantity',
        'quantity_available'
    ];

    //CALCULATED
    public function getQuantityAvailableAttribute()
    {
        return $this->quantity - $this->reserved_quantity;
    }

    public function getUsedQuantityAttribute()
    {
        return $this->reserved_quantity;
    }

    public function getCurrencyAttribute()
    {
        if ($this->purchase_entry) {
            if ($this->purchase_entry->purchase_quote_product->purchase_quote->currency == 'dolar') {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }



    public function getReservedQuantityAttribute(){
        $quantityInPreproject = $this->preproject_entries()->get()->sum('quantity');
        $quantityInProjects = $this->project_entry()
                                   ->whereDoesntHave('project_entry_liquidation')
                                   ->get()
                                   ->sum('quantity');
        return $quantityInPreproject + $quantityInProjects;
    }











    //RELATIONS
    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }

    public function project_entry()
    {
        return $this->hasMany(ProjectEntry::class);
    }

    public function purchase_entry()
    {
        return $this->hasOne(PurchasesEntry::class);
    }

    public function normal_entry()
    {
        return $this->hasOne(NormalEntry::class);
    }

    public function retrieval_entry()
    {
        return $this->hasOne(RetrievalEntry::class);
    }

    public function preproject_entries()
    {
        return $this->hasMany(PreprojectEntry::class, 'entry_id');
    }
}
