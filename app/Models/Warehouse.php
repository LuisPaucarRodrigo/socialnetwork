<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'capacity',
    ];

    public function headers()
    {
        return $this->belongsToMany(Header::class, 'warehouses_headers', 'warehouse_id', 'header_id')->withTimestamps();
    }
    
}
