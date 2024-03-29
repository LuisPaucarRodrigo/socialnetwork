<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    
    protected $fillable = ['title', 'section_id', 'subdivision_id'];

    public function section()
    {
        return $this->belongsTo(DocumentSection::class);
    }
    public function subdivision()
    {
        return $this->belongsTo(Subdivision::class);
    }
}
