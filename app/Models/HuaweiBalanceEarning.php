<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiBalanceEarning extends Model
{
    use HasFactory;

    protected $table = 'huawei_balance_earnings';

    protected $fillable = [
        'invoice_number',
        'amount',
        'invoice_date',
        'deposit_date',
        'main_amount',
        'main_op_number',
        'detraction_amount',
        'detraction_op_number',
        'detraction_date'
    ];
}
