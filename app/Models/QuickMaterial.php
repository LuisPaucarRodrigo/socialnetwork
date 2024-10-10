<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickMaterial extends Model
{
    use HasFactory;

    protected $table = 'quick_materials';

    protected $fillable = [
        'description',
        'unit',
    ];

    public function quick_materials_entries ()
    {
        return $this->hasMany(QuickMaterialsEntry::class, 'quick_material_id');
    }
}
