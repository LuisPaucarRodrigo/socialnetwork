<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollDetailExpense extends Model
{
    use HasFactory;
    protected $fillable = [
        'operation_number',
        'operation_date',
        'type',
        'payroll_detail_id',
        'account_statement_id',
    ];

    protected $appends = [
        'amount'
    ];

    public function payroll_detail(): BelongsTo
    {
        return $this->belongsTo(PayrollDetail::class, 'payroll_detail_id');
    }

    public function getAmountAttribute () {
        if($this->type==='Salary'){
            return $this->payroll_detail->net_pay;
        }
        if($this->type==='Travel'){
            return $this->payroll_detail->amount_travel_expenses;
        }
        return 0;
    }
}
