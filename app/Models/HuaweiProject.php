<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiProject extends Model
{
    use HasFactory;
    protected $table = 'huawei_projects';

    protected $fillable = [
        'date',
        'codsap',
        'description',
        'serie',
        'period',
        'hire',
        'oc_pap',
        'sites',
        'general_status',
        'status',
        'monetary_value',
        'observation',
        'project_id'
    ];

    public function project ()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
