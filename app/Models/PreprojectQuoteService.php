<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreprojectQuoteService extends Model
{
    use HasFactory;
    protected $fillable = [
        'preproject_quote_id',
        'service_id',
        'resource_entry_id',
        'days',
        'profit_margin',
        'rent_price',
    ];

    protected $appends = [
        'rent_price_with_margin'
    ];

    //RELATIONS
    public function preproject_quote_services()
    {
        return $this->belongsTo(PreProjectQuote::class,'preproject_quote_id');
    }

    public function resource_entry () {
        return $this->belongsTo(ResourceEntry::class, 'resource_entry_id');
    }

    public function service () {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function getRentPriceWithMarginAttribute()
    {
        return $this->rent_price * (1+$this->profit_margin/100);
    }
}
