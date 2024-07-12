<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiProjectEmployee extends Model
{
    use HasFactory;

    protected $table = 'huawei_project_employees';

    protected $fillable = [
        'employee_id',
        'huawei_project_id',
        'role'
    ];

    public function employee ()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function huawei_project ()
    {
        return $this->belongsTo(HuaweiProject::class, 'huawei_project_id');
    }
}
