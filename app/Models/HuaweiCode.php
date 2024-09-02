<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiCode extends Model
{
    use HasFactory;

    protected $table = 'huawei_codes';

    protected $fillable = [
        'code',
        'description'
    ];

    public function huawei_title_codes ()
    {
        return $this->hasMany(HuaweiTitleCode::class, 'huawei_code_id');
    }

    public function huawei_titles ()
    {
        return $this->belongsToMany(HuaweiTitle::class, 'huawei_title_codes');
    }

    public function huawei_project_codes ()
    {
        return $this->hasMany(HuaweiProjectCode::class, 'huawei_code_id');
    }
}
