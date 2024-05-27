<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SNSotPayment extends Model
{
    use HasFactory;
    protected $table = 'sn_sot_payment';
    protected $fillable = [
        's_n_sot_id',
        'sot_bill',
        'sot_bill_date',
        'bill',
        'bill_date',
        'charge',
        'charge_date',
    ];

    public function sot() {
        return $this->belongsTo(SNSot::class, 's_n_sot_id');
    }
}
