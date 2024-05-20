<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    use HasFactory;

    protected $table = 'codes';
    protected $fillable = [
        'code',
        'description',
    ];

    public function titleCodes()
    {
        return $this->hasMany(TitleCode::class);
    }

    public function titles()
    {
        return $this->belongsToMany(Title::class, 'title_code');
    }

}
