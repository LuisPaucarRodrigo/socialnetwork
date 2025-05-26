<?php

namespace App\Models;

use App\Constants\HuaweiConstants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Constants\ProjectConstants;

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
        'image',
        'is_accepted',
        'ec_expense_date',
        'ec_op_number',
        'ec_amount',
        'account_statement_id',
        'general_expense_id',
        'huawei_project_id',
        'user_id'
    ];
    protected $appends = [
        'real_state',
        'type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function general_expense()
    {
        return $this->belongsTo(GeneralExpense::class, 'general_expense_id');
    }

    public function huawei_project()
    {
        return $this->belongsTo(HuaweiProject::class, 'huawei_project_id');
    }

    protected static function booted()
    {
        static::creating(function ($item) {
            $as = self::findAccountStatement($item);
            $zone = HuaweiProject::find($item->huawei_project_id)->huawei_site->name ?? 'Sin zona';
            $generalExpense = GeneralExpense::create([
                'zone' => $zone,
                'expense_type' => $item->expense_type,
                'location' => $item?->huawei_monthly_project?->description ?? 'Sin descripción',
                'amount' => $item->amount,
                'operation_number' => $item->ec_op_number,
                'operation_date' => $item->ec_expense_date,
                'account_statement_id' => $as?->id,
                'type'=> ProjectConstants::EXP_TYPE_HMONTHLY
            ]);

            $item->general_expense_id = $generalExpense->id;
        });

        static::updating(function ($item) {
            $generalExpense = $item->general_expense;
            $as = self::findAccountStatement($item);
            $zone = HuaweiProject::find($item->huawei_project_id)->huawei_site->name ?? 'Sin zona';
            if ($generalExpense) {
                $generalExpense->update([
                    'zone' => $zone,
                    'expense_type' => $item->expense_type,
                    'location' => $item?->huawei_monthly_project?->description ?? 'Sin descripción',
                    'amount' => $item->amount,
                    'operation_number' => $item->ec_op_number,
                    'operation_date' => $item->ec_expense_date,
                    'account_statement_id' => $as?->id,
                    'type'=> ProjectConstants::EXP_TYPE_HMONTHLY
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

    public function getRealStateAttribute()
    {
        if ($this->is_accepted === 0){
            return 'Rechazado';
        }
        if ($this->is_accepted && $this->general_expense()->first()?->account_statement_id) {
            return 'Aceptado-Validado';
        }
        if ($this->is_accepted){
            return 'Aceptado';
        }
        return 'Pendiente';
    }

    public function getTypeAttribute()
    {
        if (in_array($this->expense_type, HuaweiConstants::getStaticExpenseTypes())) {
            return 'Fijo';
        }
        return 'Variable';
    }
    
}
