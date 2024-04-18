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
        'resource_entry_id'
    ];

    //RELATIONS
    public function preproject_quote_service()
    {
        return $this->belongsTo(PreProjectQuote::class,'preproject_quote_id');
    }
}
