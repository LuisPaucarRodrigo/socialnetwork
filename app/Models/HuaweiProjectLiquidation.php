<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiProjectLiquidation extends Model
{
    use HasFactory;

    protected $table = 'huawei_project_liquidations';

    protected $fillable = [
        'huawei_project_resource_id',
        'liquidated_quantity'
    ];

    public function huawei_project_resource ()
    {
        return $this->belongsTo(HuaweiProjectResource::class, 'huawei_project_resource_id');
    }
}
