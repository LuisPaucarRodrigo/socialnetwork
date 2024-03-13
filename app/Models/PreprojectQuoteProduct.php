<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreprojectQuoteProduct extends Model
{
    use HasFactory;
    protected $table = 'preproject_quote_products';
    protected $fillable = [
        'preproject_quote_id',
        'purchase_product_id',
        'quantity',
        'unitary_price',
        'profit_margin',
    ];

    public function purchase_product () {
        return $this->belongsTo(Purchase_product::class, 'purchase_product_id');
    }
}
