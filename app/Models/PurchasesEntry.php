<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasesEntry extends Model
{
    use HasFactory;
    protected $fillable = [
        'entry_id',
        'purchase_quote_product_id'
    ];


    //Relations
    public function entry()
    {
        return $this->belongsTo(Entry::class, 'entry_id');
    }

    public function purchase_quote_product()
    {
        return $this->belongsTo(Purchase_quotes_product::class, 'purchase_quote_product_id');
    }
}
