<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiProject extends Model
{
    use HasFactory;
    protected $table = 'huawei_projects';

    protected $fillable = [
        'huawei_site_id',
        'cost_center_id',
        'zone',
        'description',
        'status',
        'prefix',
        'macro_project',
        'assignation_date',
        'assigned_diu'
    ];

    protected $appends = [
        'code',
        'state',
        'additional_cost_total',
        'static_cost_total',
        'materials_in_project',
        'equipments_in_project',
        'materials_liquidated',
        'equipments_liquidated',
        'total_real_earnings',
        'total_real_earnings_without_deposit',
        'total_project_cost',
    ];

    public function huawei_site ()
    {
        return $this->belongsTo(HuaweiSite::class, 'huawei_site_id');
    }

    public function huawei_additional_costs ()
    {
        return $this->hasMany(HuaweiAdditionalCost::class, 'huawei_project_id');
    }

    public function huawei_static_costs ()
    {
        return $this->hasMany(HuaweiStaticCost::class, 'huawei_project_id');
    }

    public function huawei_project_employees ()
    {
        return $this->hasMany(HuaweiProjectEmployee::class, 'huawei_project_id');
    }

    public function huawei_project_resources ()
    {
        return $this->hasMany(HuaweiProjectResource::class, 'huawei_project_id');
    }

    public function huawei_project_assignations ()
    {
        return $this->hasMany(HuaweiProjectAssignation::class, 'huawei_project_id');
    }

    public function huawei_project_real_earnings ()
    {
        return $this->hasMany(HuaweiProjectRealEarning::class, 'huawei_project_id');
    }

    public function huawei_project_stages ()
    {
        return $this->hasMany(HuaweiProjectStage::class, 'huawei_project_id');
    }

    public function huawei_monthly_expenses ()
    {
        return $this->hasMany(HuaweiMonthlyExpense::class, 'huawei_project_id');
    }

    public function huawei_project_schedules ()
    {
        return $this->hasMany(HuaweiProjectSchedule::class, 'huawei_project_id');
    }

    public function cost_center ()
    {
        return $this->belongsTo(CostCenter::class, 'cost_center_id');
    }

    public function getCodeAttribute()
    {
        $year = Carbon::parse($this->assignation_date)->year;
        return $year . '-' . str_pad($this->id, 4, '0', STR_PAD_LEFT) . '-' . $this->macro_project . '-' . $this->huawei_site?->name;
    }

    public function getStateAttribute()
    {
        $resources = $this->huawei_project_resources;

        if (!$this->huawei_project_resources()) {
            return true;
        }

        $details = HuaweiEntryDetail::where('assigned_diu', $this->assigned_diu)
                ->get();

        $detail = $details->first(function ($detail) {
            return $detail->state === 'Disponible';
        });

        if ($detail){
            return false;
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

    public function getStaticCostTotalAttribute()
    {
        return $this->huawei_static_costs->sum('amount');
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

    public function getTotalRealEarningsAttribute ()
    {
        return $this->huawei_project_real_earnings->whereNotNull('deposit_date')->sum('amount');
    }

    public function getTotalRealEarningsWithoutDepositAttribute()
    {
        return $this->huawei_project_real_earnings->whereNull('deposit_date')->sum('amount');
    }

    public function getTotalProjectCostAttribute ()
    {
        return $this->additional_cost_total + $this->static_cost_total + $this->materials_in_project + $this->materials_liquidated;
    }

}
