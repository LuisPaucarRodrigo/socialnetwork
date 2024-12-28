<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiMonthlyExpense extends Model
{
    use HasFactory;

    protected $table = 'huawei_monthly_expenses';

    protected $fillable = [
        'expense_type',
        'employee',
        'zone',
        'expense_date',
        'cdp_type',
        'doc_number',
        'op_number',
        'ruc',
        'description',
        'amount',
        'image1',
        'image2',
        'image3',
        'is_accepted',
        'refund_status',
        'ec_expense_date',
        'ec_op_number',
        'ec_amount',
        'huawei_monthly_project_id',
        'account_statement_id',
        'general_expense_id',
    ];

    public function huawei_monthly_project ()
    {
        return $this->belongsTo(HuaweiMonthlyProject::class, 'huawei_monthly_project_id');
    }

    public function general_expense()
    {
        return $this->belongsTo(GeneralExpense::class, 'general_expense_id');
    }

    protected static function booted()
    {
        static::creating(function ($item) {
            $as = self::findAccountStatement($item);
            $generalExpense = GeneralExpense::create([
                'zone' => $item->zone,
                'expense_type' => $item->expense_type,
                'location' => $item?->huawei_monthly_project?->description ?? 'Sin descripción',
                'amount' => $item->amount,
                'operation_number' => $item->ec_op_number,
                'operation_date' => $item->ec_expense_date,
                'account_statement_id' => $as?->id,
            ]);

            $item->general_expense_id = $generalExpense->id;
        });

        static::updating(function ($item) {
            $generalExpense = $item->general_expense;
            $as = self::findAccountStatement($item);
            if ($generalExpense) {
                $generalExpense->update([
                    'zone' => $item->zone,
                    'expense_type' => $item->expense_type,
                    'location' => $item?->huawei_monthly_project?->description ?? 'Sin descripción',
                    'amount' => $item->amount,
                    'operation_number' => $item->ec_op_number,
                    'operation_date' => $item->ec_expense_date,
                    'account_statement_id' => $as?->id,
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
        if ($item->ec_op_number && $item->ec_expense_date) {
            $on = substr($item->ec_op_number, -6);
            return AccountStatement::where('operation_date', $item->ec_expense_date)
                ->where('operation_number', $on)->first();
        }
        return null;
    }
}
