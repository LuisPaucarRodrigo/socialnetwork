<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiMonthlyProject extends Model
{
    use HasFactory;

    protected $table = 'huawei_monthly_projects';

    protected $fillable = [
        'date',
        'description'
    ];

    public function huawei_monthly_expenses ()
    {
        return $this->hasMany(HuaweiMonthlyExpense::class, 'huawei_monthly_project_id');
    }
}
