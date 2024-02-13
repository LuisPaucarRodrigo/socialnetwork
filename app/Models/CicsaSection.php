<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CicsaSection extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function cicsa_sub_sections()
    {
        return $this->hasMany(CicsaSubSection::class);
    }

}
