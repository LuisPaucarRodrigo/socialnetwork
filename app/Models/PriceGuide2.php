<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceGuide2 extends Model
{
    use HasFactory;

    protected $table = "price_guide2";

    protected $fillable = [
        'bidding_area',
        'unit_price',
        'ha2_id'
    ];

    public function huawei_anexe2()
    {
        return $this->belongsTo(HuaweiAnexe2::class, 'ha2_id');
    }
}
