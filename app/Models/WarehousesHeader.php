<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehousesHeader extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_id',
        'header_id',
    ];

    public function header()
    {
        return $this->belongsTo(Header::class, 'header_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
    
}
