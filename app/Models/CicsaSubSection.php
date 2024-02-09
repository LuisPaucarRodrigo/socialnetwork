<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CicsaSubSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'requirements',
        'cicsa_section_id'
    ];

    public function cicsa_section()
    {
        return $this->belongsTo(CicsaSection::class);
    }

}
