<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiPriceGuide extends Model
{
    use HasFactory;

    protected $table = 'huawei_price_guides';

    protected $fillable = [
        'code',
        'description',
        'b1',
        'b2',
        'b3',
        'b4'
    ];
}
