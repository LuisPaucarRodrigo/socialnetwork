<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiBalanceCost extends Model
{
    use HasFactory;

    protected $table = 'huawei_balance_costs';

    protected $fillable = [
        'expense_type',
        'zone',
        'cost_date',
        'amount',
        'type'
    ];
}
