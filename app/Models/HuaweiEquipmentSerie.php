<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiEquipmentSerie extends Model
{
    use HasFactory;

    protected $table = 'huawei_equipment_series';

    protected $fillable = [
        'huawei_equipment_id',
        'serie_number',
    ];

    public function huawei_equipment ()
    {
        return $this->belongsTo(HuaweiEquipment::class, 'huawei_equipment_id');
    }

    public function huawei_entry_detail ()
    {
        return $this->hasOne(HuaweiEntryDetail::class, 'huawei_equipment_serie_id');
    }
}
