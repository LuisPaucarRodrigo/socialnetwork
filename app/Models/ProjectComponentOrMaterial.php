<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectComponentOrMaterial extends Model
{
    use HasFactory;
    protected $table = "project_componentormaterial";
    protected $fillable = [
        'project_id',
        'component_or_material_id',
        'quantity',
        'observation',
    ];
}
