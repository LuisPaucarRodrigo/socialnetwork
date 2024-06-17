<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceGuide1 extends Model
{
    use HasFactory;

    protected $table = "price_guide1";

    protected $fillable = [
        'bidding_area',
        'unit_price',
        'ha1_id'
    ];

    public function huawei_anexe1()
    {
        return $this->belongsTo(HuaweiAnexe1::class, 'ha1_id');
    }
}
