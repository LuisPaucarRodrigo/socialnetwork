<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreprojectCode extends Model
{
    use HasFactory;
    protected $fillable = [
        'preproject_title_id',
        'code_id',
        'status',
    ];

    protected $appends = [
        'replaceable_status'
    ];

    //CALCULATED
    public function getReplaceableStatusAttribute()
    {
        $hasImages = $this->imagecodepreprojet()->exists();

        return $hasImages ? "En proceso" : "Sin Trabajar";
    }


    //RELATIONS

    // Relación con el modelo Preproject
    public function preprojectTitle()
    {
        return $this->belongsTo(PreprojectTitle::class, 'preproject_title_id');
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
