<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickMaterialsEntry extends Model
{
    use HasFactory;

    protected $table = 'quick_materials_entries';

    protected $fillable = [
        'entry_date',
        'quantity',
        'unit_price',
        'employee',
        'observation',
        'quick_material_id'
    ];

    public function quick_material ()
    {
        return $this->belongsTo(QuickMaterial::class, 'quick_material_id');
    }

    public function quick_materials_outputs ()
    {
        return $this->hasMany(QuickMaterialsOutput::class, 'quick_material_entry_id');
    }
}
