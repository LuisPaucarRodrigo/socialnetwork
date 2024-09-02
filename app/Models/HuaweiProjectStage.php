<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiProjectStage extends Model
{
    use HasFactory;

    protected $table = 'huawei_project_stages';

    protected $fillable = [
        'huawei_project_id',
        'description'
    ];

    public function huawei_project ()
    {
        return $this->belongsTo(HuaweiProject::class, 'huawei_project_id');
    }

    public function huawei_project_codes ()
    {
        return $this->hasMany(HuaweiProjectCode::class, 'huawei_project_stage_id');
    }
}
