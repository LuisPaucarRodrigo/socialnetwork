<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceEntry extends Model
{
    use HasFactory;
    protected $fillable = [
        'state',
        'condition',
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
    public function getCurrentPriceAttribute(){
        return $this->entry_price;
    }

    //RELATIONS
    public function purchase_product()
    {
        return $this->belongsTo(Purchase_product::class,'purchase_product_id');
    }

}
