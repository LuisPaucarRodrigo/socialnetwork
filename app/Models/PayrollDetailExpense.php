<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
use App\Constants\ProjectConstants;

class PayrollDetailExpense extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_name',
        'payroll_detail_id',
        'general_expense_id',
    ];


    public function general_expense()
    {
        return $this->belongsTo(GeneralExpense::class, 'general_expense_id');
    }

    public function payroll_detail(): BelongsTo
    {
        return $this->belongsTo(PayrollDetail::class, 'payroll_detail_id');
    }


 
}
