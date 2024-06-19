<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiAnexe1 extends Model
{
    use HasFactory;
    protected $table = "huawei_anexes1";

    protected $fillable = [
        'original_name',
        'name',
        'uom',
        'payment_terms'
    ];

    public function price_guide_1 ()
    {
        return $this->hasMany(PriceGuide1::class);
    }
}
