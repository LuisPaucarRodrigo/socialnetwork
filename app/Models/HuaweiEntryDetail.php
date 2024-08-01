<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class HuaweiEntryDetail extends Model
{
    use HasFactory;

    protected $table = 'huawei_entry_details';

    protected $fillable = [
        'huawei_entry_id',
        'huawei_material_id',
        'huawei_equipment_serie_id',
        'quantity',
        'unit_price',
        'assigned_diu',
        'observation'
    ];

    protected $appends = [
        'state',
        'refund_quantity',
        'project_quantity',
        'available_quantity',
        'assigned_site',
        'antiquation_state',
        'instalation_state'
    ];

    public function huawei_entry()
    {
        return $this->belongsTo(HuaweiEntry::class, 'huawei_entry_id');
    }

    public function huawei_material()
    {
        return $this->belongsTo(HuaweiMaterial::class, 'huawei_material_id');
    }

    public function huawei_equipment_serie()
    {
        return $this->belongsTo(HuaweiEquipmentSerie::class, 'huawei_equipment_serie_id');
    }

    public function huawei_refunds ()
    {
        return $this->hasMany(HuaweiRefund::class, 'huawei_entry_detail_id');
    }

    public function huawei_project_resources()
    {
        return $this->hasMany(HuaweiProjectResource::class, 'huawei_entry_detail_id');
    }

    public function latest_huawei_project_resource()
    {
        return $this->hasOne(HuaweiProjectResource::class)->latestOfMany();
    }

    public function getStateAttribute()
    {
        if ($this->huawei_material_id) {
            if ($this->getAvailableQuantityAttribute() === 0){
                return 'No Disponible';
            }else{
                return 'Disponible';
            }
        }

        if ($this->huawei_equipment_serie_id) {
            if ($this->huawei_refunds()->count() != 0) {
                return 'Devuelto';
            }
            $projectResourcesCount = $this->huawei_project_resources()
                ->where('quantity', '>', 0)
                ->count();

            if ($projectResourcesCount != 0){
                return 'En Proyecto';
            }
            return 'Disponible';
        }
    }

    public function getInstalationStateAttribute()
    {
        $latestResource = $this->latest_huawei_project_resource;

        if ($latestResource && $latestResource->huawei_project_liquidation) {
            return 'Instalado';
        }

        return null;
    }


    public function getRefundQuantityAttribute ()
    {
        return $this->huawei_refunds()->sum('quantity');
    }

    public function getProjectQuantityAttribute()
    {
        $totalQuantity = 0;

        // Recorrer todos los recursos del proyecto
        foreach ($this->huawei_project_resources as $resource) {
            // Verificar si hay liquidación asociada y liquidated_quantity no es nulo
            if ($resource->huawei_project_liquidation && $resource->huawei_project_liquidation->liquidated_quantity !== null) {
                // Sumar la resta entre quantity y liquidated_quantity
                $totalQuantity += $resource->huawei_project_liquidation->liquidated_quantity;
            } else {
                // Si no hay liquidación o liquidated_quantity es nulo, sumar solo quantity
                $totalQuantity += $resource->quantity;
            }
        }

        return $totalQuantity;
    }


    public function getAvailableQuantityAttribute()
    {
        // Verificar si hay reembolsos asociados
        $refundQuantity = $this->huawei_refunds()->sum('quantity');
        $projectQuantity = $this->getProjectQuantityAttribute();
        // Calcular la cantidad disponible
        return $this->quantity - $refundQuantity - $projectQuantity;
    }

    public function getAntiquationStateAttribute ()
    {
        if ($this->state == 'En Proyecto' || $this->state == 'Devuelto'){
            return 'none';
        }
        $entryDate = Carbon::parse($this->huawei_entry->entry_date);

        // Obtener la fecha actual
        $now = Carbon::now();

        // Calcular la diferencia en meses
        $diffInMonths = $entryDate->diffInMonths($now);

        // Determinar el estado basado en la diferencia en meses
        if ($diffInMonths < 3) {
            return 'Green'; // Menos de 3 meses
        } elseif ($diffInMonths >= 3 && $diffInMonths < 6) {
            return 'Yellow'; // Entre 3 y 6 meses
        } elseif ($diffInMonths >= 6 && $diffInMonths < 9) {
            return 'Orange'; // Entre 6 y 9 meses
        } else {
            return 'Red'; // Mayor de 9 meses
        }
    }

    public function getAssignedSiteAttribute ()
    {
        $project = HuaweiProject::where('assigned_diu', $this->assigned_diu)->first();

        if ($project) {
            return $project->huawei_site ? $project->huawei_site->name : null;
        }

        return null;
    }
}
