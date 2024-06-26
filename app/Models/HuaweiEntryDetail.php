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
        'quantity'
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
}
