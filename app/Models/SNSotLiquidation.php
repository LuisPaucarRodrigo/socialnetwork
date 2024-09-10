<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SNSotLiquidation extends Model
{
    use HasFactory;
    protected $table = 'sn_sot_liquidation';
    protected $fillable = [
        'sot_id',
        'up_minutes',
        'liquidation',
        'down_warehouse',
        'bill_amount',
        'observations',
        'liquidation_date',
        'sot_status',
    ];

    public function sot() {
        return $this->belongsTo(SNSot::class, 'sot_id');
    }
}
