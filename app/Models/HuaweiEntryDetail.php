<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiEntryDetail extends Model
{
    use HasFactory;

    protected $table = 'huawei_entry_details';

    protected $fillable = [
        'huawei_entry_id',
        'huawei_material_id',
        'huawei_equipment_serie_id',
        'quantity',
        'unit_price'
    ];

    protected $appends = [
        'state',
        'refund_quantity',
        'available_quantity'
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
            if ($this->huawei_project_resources()->count() != 0){
                return 'En Proyecto';
            }
            return 'Disponible';
        }
    }

    public function getRefundQuantityAttribute ()
    {
        return $this->huawei_refunds()->sum('quantity');
    }

    public function getAvailableQuantityAttribute()
    {
        // Verificar si hay reembolsos asociados
        $refundQuantity = $this->huawei_refunds()->sum('quantity');
        $projectQuantity = $this->huawei_project_resources()->sum('quantity');
        // Calcular la cantidad disponible
        return $this->quantity - $refundQuantity - $projectQuantity;
    }

}
