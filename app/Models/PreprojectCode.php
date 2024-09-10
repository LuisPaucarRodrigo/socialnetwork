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
        'replaceable_status',
        'rejected_quantity',
        // 'code_name'
    ];

    //CALCULATED
    public function getReplaceableStatusAttribute()
    {
        $hasImages = $this->imagecodepreprojet()->exists();

        return $hasImages ? "En proceso" : "Sin Trabajar";
    }

    public function getRejectedQuantityAttribute()
    {
        return $this->imagecodepreprojet()->where('state', 0)->count();
    }

    // public function getCodeNameAttribute()
    // {
    //     $code = $this->code;
    //     return $code->code;
    // }


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
