<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiProjectCode extends Model
{
    use HasFactory;

    protected $table = 'huawei_project_codes';

    protected $fillable = [
        'huawei_project_stage_id',
        'huawei_code_id',
        'status'
    ];

    public function huawei_project_stage ()
    {
        return $this->belongsTo(HuaweiProjectStage::class, 'huawei_project_stage_id');
    }

    public function huawei_code ()
    {
        return $this->belongsTo(HuaweiCode::class, 'huawei_code_id');
    }

    public function huawei_project_images ()
    {
        return $this->hasMany(HuaweiProjectImage::class, 'huawei_project_code_id');
    }
}
