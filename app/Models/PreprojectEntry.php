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
        'observation',
        'unitary_price',
        'state'
    ];

    //Relations
    public function preproject ()
    {
        return $this->belongsTo(Preproject::class,'preproject_id');
    } 

    public function entry (){
        return $this->belongsTo(Entry::class,'entry_id');
    }

}
