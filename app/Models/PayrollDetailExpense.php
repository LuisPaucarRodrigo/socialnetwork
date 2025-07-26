<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Constants\PintConstants;
use Illuminate\Support\Facades\Log;
use App\Constants\ProjectConstants;

class PayrollDetailExpense extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_name',
        'payroll_detail_id',
        'general_expense_id',
        'photo',
    ];


    public function general_expense()
    {
        return $this->belongsTo(GeneralExpense::class, 'general_expense_id');
    }

    public function payroll_detail(): BelongsTo
    {
        return $this->belongsTo(PayrollDetail::class, 'payroll_detail_id');
    }


    public function getRealStateAttribute() {
        if ($this->general_expense()->first()->account_statement_id) {
            return PintConstants::ACEPTADO_VALIDADO;
        }
        return PintConstants::PENDIENTE;
    }

 
}
