<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiProductLoad extends Model
{
    use HasFactory;

    protected $tables = "huawei_product_loads";

    protected $fillable = [
        'name',
        'path'
    ];
}
