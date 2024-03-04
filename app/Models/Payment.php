<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;


class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount',
        'description',
        'state',
        'operation_number',
        'date',
        'payment_doc',
        'purchase_quote_id'
    ];

    public function purchase_quote()
    {
        return $this->belongsTo(Purchase_quote::class, 'purchase_quote_id');
    }

    protected $append = [
        'cod_payment'
    ];

    public function getCodPaymentAttribute()
    {
        return 'RP' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }
}
