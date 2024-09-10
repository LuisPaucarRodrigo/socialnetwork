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
        'cost_date',
        'amount',
        'huawei_project_id',
    ];

    public function huawei_project ()
    {
        return $this->belongsTo(HuaweiProject::class, 'huawei_project_id');
    }
}
