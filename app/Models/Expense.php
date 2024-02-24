<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $fillable = [
        'type_cdp',
        'gang',
        'person',
        'date',
        'number',
        'series',
        'ruc',
        'price',
        'description_expenses',
        'type_expenses',
        'percentaje'
    ];

    protected $appends = [
        'price_without_igv'
    ];

    public function getPriceWithoutIgvAttribute()
    {
        return number_format($this->price - ($this->price * ($this->percentaje/100)), 2);
    }
}
