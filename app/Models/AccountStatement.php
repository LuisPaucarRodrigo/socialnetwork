<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountStatement extends Model
{
    use HasFactory;
    protected $fillable = [
        'operation_date',
        'operation_number',
        'description',
        'charge',
        'payment',
        'balance',
    ];

    public function additional_costs () {
        return $this->hasMany(AdditionalCost::class, 'account_statement_id');
    }

    public function static_costs () {
        return $this->hasMany(AdditionalCost::class, 'account_statement_id');
    }

}
