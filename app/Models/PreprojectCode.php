<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreprojectCode extends Model
{
    use HasFactory;
    protected $fillable = [
        'preproject_id',
        'code_id',
        'status',
    ];

    protected $appends = [
        'replaceable_status'
    ];

    //CALCULATED
    public function getReplaceableStatusAttribute()
    {
        // Verificar si hay al menos una imagen relacionada
        $hasImages = $this->imagecodepreprojet()->exists();

        // Determinar el estado basado en la presencia de imágenes
        return $this->status === "Aprobado" ? "Aprobado" : ($hasImages ? "En proceso" : "Sin Trabajar");
    }


    //RELATIONS

    // Relación con el modelo Preproject
    public function preproject()
    {
        return $this->belongsTo(Preproject::class, 'preproject_id');
    }

    // Relación con el modelo Code
    public function code()
    {
        return $this->belongsTo(Code::class, 'code_id');
    }

    public function imagecodepreprojet()
    {
        return $this->HasMany(Imagespreproject::class);
    }
}
