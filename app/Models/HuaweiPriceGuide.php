<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiPriceGuide extends Model
{
    use HasFactory;

    protected $table = 'huawei_price_guides';
    //buena paucar

    protected $fillable = [
        'code',
        'description',
        'unit',
        'b1',
        'b2',
        'b3',
        'b4',
        'cost_center_id',
    ];

    //relations
    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class, 'cost_center_id');
    }
}
