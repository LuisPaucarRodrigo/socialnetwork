<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiAdditionalCost extends Model
{
    use HasFactory;

    protected $table = 'huawei_additional_costs';

    protected $fillable = [
        'expense_type',
        'zone',
        'employee',
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
        'huawei_project_id',
    ];

    public function huawei_project ()
    {
        return $this->belongsTo(HuaweiProject::class, 'huawei_project_id');
    }
}
