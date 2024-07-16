<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiProject extends Model
{
    use HasFactory;
    protected $table = 'huawei_projects';

    protected $fillable = [
        'name',
        'huawei_site_id',
        'description',
        'ot',
        'pre_report',
        'status'
    ];

    protected $appends = [
        'code',
        'state'
    ];

    public function huawei_site ()
    {
        return $this->belongsTo(HuaweiSite::class, 'huawei_site_id');
    }

    public function huawei_additional_costs ()
    {
        return $this->hasMany(HuaweiAdditionalCost::class, 'huawei_project_id');
    }

    public function huawei_project_employees ()
    {
        return $this->hasMany(HuaweiProjectEmployee::class, 'huawei_project_id');
    }

    public function huawei_project_resources ()
    {
        return $this->hasMany(HuaweiProjectResource::class, 'huawei_project_id');
    }

    public function getCodeAttribute ()
    {
        $year = date('Y', strtotime($this->created_at));
        $totalYearProjects = HuaweiProject::whereYear('created_at', $year)->count();
        $formattedTotal = str_pad($totalYearProjects, 3, '0', STR_PAD_LEFT);
        return $year . '-' . $formattedTotal . '-' . strtoupper(substr($this->description, 0, 5));
    }

    public function getStateAttribute()
    {
        $resources = $this->huawei_project_resources;

        if (!$this->huawei_project_resources()) {
            return true;
        }

        foreach ($resources as $resource) {
            if ($resource->quantity > 0) {
                if (!$resource->huawei_project_liquidation) {
                    return false;
                }
            }
        }
        return true;
    }

}
