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
        'account_statement_id'
    ];

    public function huawei_monthly_project ()
    {
        return $this->belongsTo(HuaweiMonthlyProject::class, 'huawei_monthly_project_id');
    }
}
