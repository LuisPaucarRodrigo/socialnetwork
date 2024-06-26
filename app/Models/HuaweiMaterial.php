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
    ];

    public function brand_model ()
    {
        return $this->belongsTo(BrandModel::class, 'model_id');
    }

    public function huawei_entry_details ()
    {
        return $this->hasMany(HuaweiEntryDetail::class, 'huawei_material_id');
    }
}
