<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreProjectQuoteItem extends Model
{
    use HasFactory;
    protected $table = "preproject_quote_items";
    protected $fillable = [
        'description',
        'unit',
        'days',
        'quantity',
        'unit_price',
        'profit_margin',
        'preproject_quote_id',
    ];

    public function quote () {
        return $this->belongsTo(PreProjectQuote::class, 'preproject_quote_id');
    }
}
