<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiAnexe2 extends Model
{
    use HasFactory;
    protected $table = "huawei_anexes2";

    protected $fillable = [
        'original_name',
        'name',
        'uom',
        'payment_terms'
    ];

    public function price_guide_2 ()
    {
        return $this->hasMany(PriceGuide2::class);
    }
}
