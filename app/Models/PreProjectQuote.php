<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreProjectQuote extends Model
{
    use HasFactory;
    protected $table = 'preproject_quotes';
    protected $fillable = [
        "name",
        "date",
        "supervisor",
        "boss",
        "rev",
        "deliverable_time",
        "validity_time",
        "deliverable_place",
        "payment_type",
        "observations",
        "preproject_id",
        "state"
    ] ;

    protected $appends = [
        'total_amount_margin',
        'total_amount_no_margin',
    ];

    public function preproject () {
        return $this->belongsTo(Preproject::class,"preproject_id");
    }

    public function items() {
        return $this->hasMany(PreProjectQuoteItem::class,"preproject_quote_id");
    }
    public function products() {
        return $this->hasMany(PreprojectQuoteProduct::class,"preproject_quote_id");
    }

    public function getTotalAmountNoMarginAttribute() {
        $totalItems = $this->items()->get()->sum(function ($item) {
            return $item->days * 
                    $item->quantity * 
                    $item->unit_price;
        });
        $totalProducts = $this->products()->get()->sum(function ($item) {
            return $item->quantity * $item->unitary_price;
        });
        return $totalItems + $totalProducts;
    }

    public function getTotalAmountMarginAttribute() {
        $totalItems = $this->items()->get()->sum(function ($item) {
            return $item->days *
                    $item->quantity *
                    $item->unit_price *
                    (1+ $item->profit_margin/100);
        });
        $totalProducts = $this->products()->get()->sum(function ($item) {
            return $item->quantity * $item->unitary_price * (1+ $item->profit_margin/100);
        });
        return $totalItems + $totalProducts;
    }
}
