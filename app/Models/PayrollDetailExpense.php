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
        'operation_number',
        'operation_date',
        'type',
        'payroll_detail_id',
        'account_statement_id',
        'general_expense_id',
    ];

    protected $appends = [
        'amount'
    ];

    public function general_expense()
    {
        return $this->belongsTo(GeneralExpense::class, 'general_expense_id');
    }

    public function payroll_detail(): BelongsTo
    {
        return $this->belongsTo(PayrollDetail::class, 'payroll_detail_id');
    }

    public function getAmountAttribute () {
        if($this->type==='Salary'){
            return $this->payroll_detail->net_pay;
        }
        if($this->type==='Travel'){
            return $this->payroll_detail?->amount_travel_expenses ?? 0;
        }
        return 0;
    }

    protected static function booted()
    {
        static::creating(function ($item) {
            $as = self::findAccountStatement($item);
            $generalExpense = GeneralExpense::create([
                'zone' => 'Nómina',
                            'expense_type' => ($item->type === 'Salary' )
                ? 'Sueldo' :( $item->type === 'Travel' 
                ? 'Viáticos' 
                : 'error' ),
                'location' => $item?->payroll_detail?->employee_name ?? 'Sin descripción',
                'amount' => $item->amount,
                'operation_number' => $item->operation_number,
                'operation_date' => $item->operation_date,
                'account_statement_id' => $as?->id,
                'type'=> ProjectConstants::EXP_TYPE_PAYROLL
            ]);

            $item->general_expense_id = $generalExpense->id;
        });

        static::updating(function ($item) {
            $generalExpense = $item->general_expense;
            $as = self::findAccountStatement($item);
            if ($generalExpense) {
                $generalExpense->update([
                    'zone' => 'Nómina',
                   'expense_type' => ($item->type === 'Salary' )
                    ? 'Sueldo' :( $item->type === 'Travel' 
                    ? 'Viáticos' 
                    : 'error' ),
                    'location' => $item?->payroll_detail?->employee_name ?? 'Sin descripción',
                    'amount' => $item->amount,
                    'operation_number' => $item->operation_number,
                    'operation_date' => $item->operation_date,
                    'account_statement_id' => $as?->id,
                    'type'=> ProjectConstants::EXP_TYPE_PAYROLL
                ]);
            }
        });

        static::deleting(function ($item) {
            $generalExpense = $item->general_expense;
            if ($generalExpense) {
                $generalExpense->delete();
            }
        });
    }

   
    protected static function findAccountStatement($item)
    {
        if ($item->operation_number && $item->operation_date) {
            $on = substr($item->operation_number, -6);
            return AccountStatement::where('operation_date', $item->operation_date)
                ->where('operation_number', $on)->first();
        }
        return null;
    }

}
