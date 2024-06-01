<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinuteMaterial extends Model
{
    use HasFactory;
    protected $table = 'minute_materials';
    protected $fillable = [
        'snsotop_id',
        'material',
        'quantity',
    ];
}
