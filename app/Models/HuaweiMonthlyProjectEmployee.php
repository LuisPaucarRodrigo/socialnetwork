<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiMonthlyProjectEmployee extends Model
{
    use HasFactory;

    protected $table = 'huawei_monthly_project_employees';

    protected $fillable = [
        'huawei_mp_id',
        'employee_id'
    ];

    public function huawei_monthly_project ()
    {
        return $this->belongsTo(HuaweiMonthlyProject::class, 'huawei_mp_id');
    }

    public function employee ()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
