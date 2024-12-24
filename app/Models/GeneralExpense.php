<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralExpense extends Model
{
    use HasFactory;
    protected $fillable = [
        'zone',
        'expense_type',
        'location',
        'amount',
        'operation_number',
        'operation_date',
        'account_statement_id',
    ];

    protected $casts = [
        'amount' => 'double',
    ];

}
