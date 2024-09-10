<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiSpecialRefund extends Model
{
    use HasFactory;

    protected $table = 'huawei_special_refunds';

    protected $fillable = [
        'description',
        'diu',
        'quantity',
        'observation'
    ];
}
