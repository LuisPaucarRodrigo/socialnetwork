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
        "observations",
        "preproject_id",
        "state"
    ] ;

    public function preproject () {
        return $this->belongsTo(Preproject::class,"preproject_id");
    }

    public function items() {
        return $this->hasMany(PreProjectQuoteItem::class,"preproject_quote_id");
    }
    public function products() {
        return $this->hasMany(PreprojectQuoteProduct::class,"preproject_quote_id");
    }
}
