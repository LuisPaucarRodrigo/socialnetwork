<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicesLiquidation extends Model
{
    use HasFactory;
    protected $table = "services_liquidations";
    protected $fillable = [
        'preproject_quote_service_id',
        'observations',
    ];

    //RELATIONS
    public function purchase_product()
    {
        return $this->belongsTo(PreprojectQuoteService::class,'preproject_quote_service_id');
    }
}
