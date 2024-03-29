<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;


class Purchase_quotes_product extends Model
{
    use HasFactory;
    protected $table = 'purchase_quotes_products';
    protected $fillable = [
        'quantity', 
        'unitary_amount', 
        'purchase_quote_id',
        'purchase_product_id', 
    ];

    public function purchase_product()
    {
        return $this->belongsTo(Purchase_product::class, 'purchase_product_id');
    }

    public function purchase_quote()
    {
        return $this->belongsTo(Purchase_quote::class, 'purchase_quote_id');
    }

}

 