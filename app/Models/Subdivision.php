<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subdivision extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'section_id'];

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
    public function section()
    {
        return $this->belongsTo(DocumentSection::class);
    }
}