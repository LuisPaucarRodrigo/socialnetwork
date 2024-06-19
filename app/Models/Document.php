<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'section_id', 'subdivision_id'];

    protected $appends = [
        'extension'
    ];

    public function section()
    {
        return $this->belongsTo(DocumentSection::class);
    }
    public function subdivision()
    {
        return $this->belongsTo(Subdivision::class);
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
