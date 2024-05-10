<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TitleCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_id',
        'code_id'
    ];

    public function title()
    {
        return $this->belongsTo(Title::class, 'title_id');
    }

    public function code()
    {
        return $this->belongsTo(Code::class, 'code_id');
    }
}
