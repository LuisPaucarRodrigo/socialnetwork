<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiMaterial extends Model
{
    use HasFactory;

    protected $table = 'huawei_materials';

    protected $fillable = [
        'name',
        'claro_code',
        'model_id',
        'unit'
    ];

    protected $appends = [
        'quantity',
        'available_quantity'
    ];

    public function brand_model ()
    {
        return $this->belongsTo(BrandModel::class, 'model_id');
    }

    public function huawei_entry_details ()
    {
        return $this->hasMany(HuaweiEntryDetail::class, 'huawei_material_id');
    }

    public function getQuantityAttribute()
    {
        return $this->huawei_entry_details()->sum('quantity');
    }

    public function getAvailableQuantityAttribute ()
    {
        // Obtener todas las instancias relacionadas de HuaweiEntryDetail
        $entryDetails = $this->huawei_entry_details;

        // Sumar las cantidades disponibles de cada instancia
        $totalAvailableQuantity = $entryDetails->reduce(function ($carry, $entryDetail) {
            return $carry + $entryDetail->available_quantity;
        }, 0);

        return $totalAvailableQuantity;
    }
}
