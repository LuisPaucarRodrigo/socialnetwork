<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'subdivision_id',
        'exp_date'
    ];

    protected $appends = [
        'extension'
    ];

    public function subdivision()
    {
        return $this->belongsTo(Subdivision::class, 'subdivision_id');
    }

    public function getExtensionAttribute()
    {
        // Obtiene el nombre del archivo completo
        $fileName = $this->title;

        // Utiliza la función pathinfo para obtener la información del archivo
        $fileInfo = pathinfo($fileName);

        // Retorna solo la extensión del archivo
        return $fileInfo['extension'] ?? null;
    }
}
