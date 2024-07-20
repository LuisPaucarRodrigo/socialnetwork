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
        'state',
        'additional_cost_total',
        'materials_in_project',
        'equipments_in_project',
        'materials_liquidated',
        'equipments_liquidated',
        'total_project_cost'
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

    public function getCodeAttribute()
    {
        $year = date('Y', strtotime($this->created_at));
        $totalYearProjects = HuaweiProject::whereYear('created_at', $year)->count();
        $projectNumber = HuaweiProject::whereYear('created_at', $year)
                                        ->where('id', '<=', $this->id)
                                        ->count();
        $formattedProjectNumber = str_pad($projectNumber, 3, '0', STR_PAD_LEFT);
        return $year . '-' . $formattedProjectNumber . '-' . strtoupper(substr($this->description, 0, 5));
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

    public function getAdditionalCostTotalAttribute()
    {
        return $this->huawei_additional_costs->sum('amount');
    }

    public function getMaterialsInProjectAttribute ()
    {
        return $this->huawei_project_resources
            ->where('quantity', '>', 0)
            ->whereNull('huawei_project_liquidation')
            ->filter(function ($resource) {
                return $resource->huawei_entry_detail && !is_null($resource->huawei_entry_detail->huawei_material_id);
            })
            ->sum(function ($resource) {
                return $resource->quantity * ($resource->huawei_entry_detail->unit_price ?? 0);
            });
    }


    public function getEquipmentsInProjectAttribute ()
    {
        return $this->huawei_project_resources
            ->where('quantity', '>', 0)
            ->whereNull('huawei_project_liquidation')
            ->filter(function ($resource) {
                return $resource->huawei_entry_detail && !is_null($resource->huawei_entry_detail->huawei_equipment_serie_id);
            })
            ->sum(function ($resource) {
                return $resource->quantity * ($resource->huawei_entry_detail->unit_price ?? 0);
            });
    }

    public function getMaterialsLiquidatedAttribute ()
    {
        return $this->huawei_project_resources
            ->where('quantity', '>', 0)
            ->filter(function ($resource) {
                return $resource->huawei_project_liquidation && !is_null($resource->huawei_entry_detail->huawei_material_id);
            })
            ->sum(function ($resource) {
                return $resource->huawei_project_liquidation->liquidated_quantity * ($resource->huawei_entry_detail->unit_price ?? 0);
            });
    }

    public function getEquipmentsLiquidatedAttribute ()
    {
        return $this->huawei_project_resources
            ->where('quantity', '>', 0)
            ->filter(function ($resource) {
                return $resource->huawei_project_liquidation && !is_null($resource->huawei_entry_detail->huawei_equipment_serie_id);
            })
            ->sum(function ($resource) {
                return $resource->huawei_project_liquidation->liquidated_quantity * ($resource->huawei_entry_detail->unit_price ?? 0);
            });
    }

    public function getTotalProjectCostAttribute ()
    {
        return $this->additional_cost_total + $this->materials_in_project + $this->materials_liquidated + $this->equipments_in_project + $this->equipments_liquidated;
    }
}
