<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function warehouse()
    {
        return $this->belongsToMany(Warehouse::class, 'warehouses_headers', 'warehouse_id', 'product_id')->withTimestamps();
    }
    
    public function warehouseHeaders()
    {
        return $this->hasMany(WarehousesHeader::class, 'product_id');
    }
}
