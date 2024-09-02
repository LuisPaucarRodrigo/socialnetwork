<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiProjectImage extends Model
{
    use HasFactory;

    protected $table = 'huawei_project_images';

    protected $fillable = [
        'huawei_project_code_id',
        'image',
        'description',
        'observation',
        'lat',
        'lon',
        'state'
    ];

    public function huawei_project_code ()
    {
        return $this->belongsTo(HuaweiProjectCode::class, 'huawei_project_code_id');
    }
}
