<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase_quote extends Model
{
    use HasFactory;
    protected $fillable = [
        'quote_deadline', 
        'purchase_doc', 
        'response', 
        'state',
        'igv',
        'deliverable_time',
        'payment_type',
        'account_number',
        'code',
        'purchasing_request_id',
        'provider_id',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

    public function purchasing_requests()
    {
        return $this->belongsTo(Purchasing_request::class, 'purchasing_request_id');
    }

    public function purchase_order()
    {
        return $this->hasOne(Purchase_order::class);
    }
}
