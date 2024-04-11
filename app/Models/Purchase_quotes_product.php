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

    protected $appends = [
        "unitary_amount_no_igv"
    ];

    public function purchase_product(){
        return $this->belongsTo(Purchase_product::class, 'purchase_product_id');
    }

    public function purchase_quote(){
        return $this->belongsTo(Purchase_quote::class, 'purchase_quote_id');
    }

    public function purchase_entry(){
        return $this->hasOne(PurchasesEntry::class);
    }

    public function getUnitaryAmountNoIgvAttribute () {
        $unitary_amount = $this->unitary_amount;
        $igv_percentage = $this->purchase_quote->igv_percentage;
        $currency = $this->purchase_quote->currency;
        $change_value = $this->purchase_quote->change_value;
        if ($currency === "sol"){
            return $unitary_amount/(1 + $igv_percentage);
        } else if ($currency === "dolar") {
            return ($change_value !== null ? $change_value : 1)
                    *$unitary_amount/(1 + $igv_percentage); 
        }
    }

}

 