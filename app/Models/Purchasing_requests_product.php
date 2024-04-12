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

    protected $appends = [
        'actual_quotes_quantity'
    ];

    public function purchase_product()
    {
        return $this->belongsTo(Purchase_product::class, 'purchase_product_id');
    }

    public function purchasing_request() {
        return $this->belongsTo(Purchasing_request::class, 'purchasing_request_id');
    }

    public function getActualQuotesQuantityAttribute () {
        $pr = $this->purchasing_request()
            ->with('purchase_quotes.purchase_quote_products')
            ->first();
        $pqProductsTotal = 0;
        foreach ($pr->purchase_quotes as $quote){
            if (($quote->state === null || $quote->state === true)
                && ($quote->preproject_state === null || $quote->preproject_state === true) ){
                $key = array_search(
                    $this->purchase_product->id, 
                    array_column($quote->purchase_quote_products->toArray(), 'purchase_product_id')
                );
                $product = $quote->purchase_quote_products[$key];
                $pqProductsTotal += $product->quantity;
            }
        }


        return $pqProductsTotal;
    }

}

