<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Header extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type'
    ];

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'warehouses_headers', 'header_id', 'warehouse_id')->withTimestamps();
    }
}
