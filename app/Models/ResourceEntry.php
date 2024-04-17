<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceEntry extends Model
{
    use HasFactory;
    protected $fillable = [
        'entry_date',
        'serial_number',
        'referral_guide',
        'entry_price',
        'state',
        'purchase_product_id'
    ];

    protected $appends = [
        'current_price'
    ];

    //CALCULATED
    public function getCurrentPriceAttribute()
    {
    }

    //RELATIONS
    public function purchase_product()
    {
        return $this->belongsTo(Purchase_product::class,'purchase_product_id');
    }

}
