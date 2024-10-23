<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickMaterialsOutput extends Model
{
    use HasFactory;

    protected $table = 'quick_materials_outputs';

    protected $fillable = [
        'output_date',
        'output_quantity',
        'quick_material_entry_id',
        'output_employee',
        'output_observation',
        'huawei_project_id'
    ];

    public function quick_material_entry ()
    {
        return $this->belongsTo(QuickMaterialsEntry::class, 'quick_material_entry_id');
    }

    public function huawei_project ()
    {
        return $this->belongsTo(HuaweiProject::class, 'huawei_project_id');
    }
}
