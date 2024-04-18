<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'rent_price',
        'purchase_product_id'
    ];


    //RELATIONS
    public function purchase_product()
    {
        return $this->belongsTo(Purchase_product::class,'purchase_product_id');
    }
}
