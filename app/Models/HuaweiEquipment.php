<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiEquipment extends Model
{
    use HasFactory;

    protected $table = 'huawei_equipments';

    protected $fillable = [
        'name',
        'claro_code',
        'model_id'
    ];

    public function brand_model ()
    {
        return $this->belongsTo(BrandModel::class, 'model_id');
    }

    public function huawei_equipment_series ()
    {
        return $this->hasMany(HuaweiEquipmentSerie::class, 'huawei_equipment_id');
    }
}
