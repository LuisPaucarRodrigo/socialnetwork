<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiSite extends Model
{
    use HasFactory;
        //buena paucar

    protected $table = 'huawei_sites';

    protected $fillable = [
        'name'
    ];

    public function huawei_projects ()
    {
        return $this->hasMany(HuaweiProject::class, 'huawei_site_id');
    }
}
