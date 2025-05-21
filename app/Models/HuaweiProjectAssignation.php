<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiProjectAssignation extends Model
{
    use HasFactory;
    
    protected $table = 'huawei_project_assignations';

    protected $fillable = [
        'huawei_project_id',
        'po',
        'assignation_date',
        'description'
    ];

    //relations
    public function huawei_project ()
    {
        return $this->belongsTo(HuaweiProject::class, 'huawei_project_id');
    }
    public function huawei_project_earnings ()
    {
        return $this->hasMany(HuaweiProjectEarning::class, 'huawei_pa_id');
    }
}
