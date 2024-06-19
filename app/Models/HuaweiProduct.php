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
        'zone',
        'pg1_id',
        'pg2_id',
    ];

    public function huawei_product_load ()
    {
        return $this->belongsTo(HuaweiProductLoad::class, 'hpl_id');
    }

    public function price_guide1()
    {
        return $this->belongsTo(PriceGuide1::class, 'pg1_id');
    }

    public function price_guide2()
    {
        return $this->belongsTo(PriceGuide2::class, 'pg2_id');
    }


}
