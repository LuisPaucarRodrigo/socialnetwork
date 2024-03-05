<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;


class Purchasing_requests_product extends Model
{
    use HasFactory;
    protected $fillable = [
        'purchase_product_id', 
        'purchasing_request_id', 
        'quantity',
    ];

    public function purchase_product()
    {
        return $this->belongsTo(Purchase_product::class, 'purchase_product_id');
    }

    public function purchasing_request()
    {
        return $this->belongsTo(Purchasing_request::class, 'purchasing_request_id');
    }

}

