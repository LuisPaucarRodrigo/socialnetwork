<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreprojectEntry extends Model
{
    use HasFactory;
    protected $table = 'preproject_entries';
    protected $fillable = [
        'preproject_id',
        'entry_id',
        'quantity',
        'margin',
        'observation',
        'unitary_price',
        'state'
    ];

    protected $appends = [
        'unitary_price_with_margin'
    ];

    //Relations
    public function preproject ()
    {
        return $this->belongsTo(Preproject::class,'preproject_id');
    } 

    public function entry (){
        return $this->belongsTo(Entry::class,'entry_id');
    }

    public function getUnitaryPriceWithMarginAttribute()
    {
        return $this->unitary_price * (1+$this->margin/100);
    }

}
