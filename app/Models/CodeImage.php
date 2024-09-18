<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodeImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'code_id',
    ];

    public function code()
    {
        return $this->belongsTo(Code::class, 'code_id');
    }

    protected static function booted()
    {
        static::deleting(function ($codeImage) {
            $filePath = public_path('image/imageCode/' . $codeImage->image);

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        });
    }
}
