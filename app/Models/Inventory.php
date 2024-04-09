<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    protected $fillable = [
        'purchase_product_id',
        'warehouse_id',
    ];

    protected $appends = [
        'available_quantity'
    ];

    //Relations
    public function purchase_product()
    {
        return $this->belongsTo(Purchase_product::class, 'purchase_product_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function entry()
    {
        return $this->hasMany(Entry::class);
    }

    public function getAvailableQuantityAttribute()
    {
        return $this->entry()->sum('quantity');
    }
}
