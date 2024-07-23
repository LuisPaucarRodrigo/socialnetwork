<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiProjectEarning extends Model
{
    use HasFactory;

    protected $table = 'huawei_project_earnings';

    protected $fillable = [
        'description',
        'amount',
        'huawei_project_id'
    ];

    public function huawei_project ()
    {
        return $this->belongsTo(HuaweiProject::class, 'huawei_project_id');
    }
}
