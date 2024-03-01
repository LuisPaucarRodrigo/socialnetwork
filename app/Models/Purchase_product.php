<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;


class Purchase_product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 
        'code', 
        'unit',
        'description',
    ];

    public function purchasing_request_product()
    {
        return $this->hasMany(Purchasing_requests_product::class);
    }

    public function purchase_quote_product() 
    {
        return $this->hasMany(Purchase_quote_product::class);
    }

    public function purchasing_requests()
    {
        return $this->belongsToMany(Purchasing_request::class, 'purchasing_requests_products', 'purchase_product_id', 'purchasing_request_id')->withTimestamps();
    }

}

