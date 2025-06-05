<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiProjectEmployee extends Model
{
    use HasFactory;

    protected $table = 'huawei_project_employees';

    protected $fillable = [
        'huawei_project_schedule_id',
        'employee',
    ];

    //relations
    public function huawei_project_schedule()
    {
        return $this->belongsTo(HuaweiProjectSchedule::class, 'huawei_project_schedule_id');
    }
}
