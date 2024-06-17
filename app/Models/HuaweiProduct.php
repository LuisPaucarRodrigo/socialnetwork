<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiProduct extends Model
{
    use HasFactory;
    protected $table = 'huawei_products';
    protected $fillable = [
        'hpl_id',
        'name',
        'anexe_name',
        'anexe_unit',
        'quantity',
        'pg1_id',
        'pg2_id',
    ];

    
}
