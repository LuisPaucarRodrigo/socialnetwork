<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'name',
        // 'location',
        // 'manager'
        'name',
        'description',
        'person_in_charge',
        'category'
    ];

    //Relations
    public function customer_warehouse()
    {
        return $this->hasMany(CustomerWarehouse::class);
    }

    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }

}
