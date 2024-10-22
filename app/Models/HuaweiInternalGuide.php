<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiInternalGuide extends Model
{
    use HasFactory;

    protected $table = 'huawei_internal_guides';

    protected $fillable = [
        'name'
    ];

}
