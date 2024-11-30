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

    public function payroll_detail(): BelongsTo
    {
        return $this->belongsTo(PayrollDetail::class, 'payroll_detail_id');
    }
}
