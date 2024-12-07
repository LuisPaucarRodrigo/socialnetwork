<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AccountStatement extends Model
{
    use HasFactory;
    protected $fillable = [
        'operation_date',
        'operation_number',
        'description',
        'charge',
        'payment',
    ];

    public function additional_costs()
    {
        return $this->hasMany(AdditionalCost::class, 'account_statement_id');
    }

    public function static_costs()
    {
        return $this->hasMany(StaticCost::class, 'account_statement_id');
    }

    public function pext_project_expenses()
    {
        return $this->hasMany(PextProjectExpense::class, 'account_statement_id');
    }

    public function payroll_details_expenses()
    {
        return $this->hasMany(PayrollDetailExpense::class, 'account_statement_id');
    }

    public function getStateAttribute()
    {
        
        if ($this->operation_number === null) {
            return 'Validado';
        }
        if ($this->payment) {
            return 'Abono';
        }
        if ($this->charge) {
            $totalAC = $this->additional_costs()->get()->sum('amount');
            $totalSC = $this->static_costs()->get()->sum('amount');
            $totalPE = $this->pext_project_expenses()->get()->sum('amount');

            $totalSP = $this->payroll_details_expenses()
                ->with('payroll_detail')
                ->get()
                ->sum(function ($sp) {
                    if ($sp->type === 'Salary') {
                        return $sp->payroll_detail->net_pay;
                    }
                    if ($sp->type === 'Travel') {
                        return $sp->payroll_detail->amount_travel_expenses;
                    }
                    return 0;
                });
            $total = $totalAC + $totalSC + $totalPE + $totalSP;
            $chargeFormatted = number_format($this->charge, 2, '.', '');
            $totalFormatted = number_format($total, 2, '.', '');
    
            // Convertir a float para realizar la comparación
            if ((float)$totalFormatted >= (float)$chargeFormatted) {
                return 'Validado';
            } elseif ((float)$totalFormatted > 0) {
                return 'Por validar';
            } else {
                return 'No validado';
            }
        }
    }

}
