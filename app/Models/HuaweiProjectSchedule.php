<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiProjectSchedule extends Model
{
    use HasFactory;

    protected $table = 'huawei_project_schedules';

    protected $fillable = [
        'activity',
        'days',
        'huawei_project_id'
    ];

    //relations 
    public function huawei_project()
    {
        return $this->belongsTo(HuaweiProject::class, 'huawei_project_id');
    }
}
