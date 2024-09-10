<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntryDocument extends Model
{
    use HasFactory;
    protected $fillable = [
        'registration_form',
        'contract',
        'certificate_discharge',
        'reading_sanctions',
        'reading_procedures',
        'annex_4_induction',
        'fixed_documentation_id'
    ];

    //Relations
    public function fixed_documentation(){
        return $this->belongsTo(FixedDocumentation::class,'fixed_documentation_id');
    }
}
