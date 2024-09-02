<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiTitle extends Model
{
    use HasFactory;

    protected $table = 'huawei_titles';

    protected $fillable = [
        'name'
    ];

    public function huawei_title_codes ()
    {
        return $this->hasMany(HuaweiTitleCode::class, 'huawei_title_id');
    }

    public function huawei_codes ()
    {
        return $this->belongsToMany(HuaweiCode::class, 'huawei_title_codes');
    }
}
