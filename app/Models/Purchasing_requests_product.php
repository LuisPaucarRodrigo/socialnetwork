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
        'actual_quotes_quantity',
        'pending_quotes_quantity',
        'accepted_quotes_quantity',
        'completed_state',
    ];

    public function purchase_product()
    {
        return $this->belongsTo(Purchase_product::class, 'purchase_product_id');
    }

    public function purchasing_request() {
        return $this->belongsTo(Purchasing_request::class, 'purchasing_request_id');
    }

    public function getActualQuotesQuantityAttribute () {
        return $this->getAcceptedQuotesQuantityAttribute()
                + $this->getPendingQuotesQuantityAttribute();
    }


    public function getPendingQuotesQuantityAttribute () {
        $pr = $this->purchasing_request()
        ->with('purchase_quotes.purchase_quote_products')
        ->first();
        $pqProductsTotal = 0;
        foreach ($pr->purchase_quotes as $quote){
            if (($quote->state === null)
                && ($quote->preproject_state === null || $quote->preproject_state === 1) ){
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


    public function getAcceptedQuotesQuantityAttribute () {
        $pr = $this->purchasing_request()
        ->with('purchase_quotes.purchase_quote_products')
        ->first();
        $pqProductsTotal = 0;
        foreach ($pr->purchase_quotes as $quote){
            if (($quote->state === 1)
                && ($quote->preproject_state === null || $quote->preproject_state === 1) ){
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

    
    public function getCompletedStateAttribute() {
        if( $this->getAcceptedQuotesQuantityAttribute() > $this->quantity) {
            throw new \Exception('Error en productos de cotizaciones cantidades inválidas');
        }
        return $this->getAcceptedQuotesQuantityAttribute() === $this->quantity;
    }

}

