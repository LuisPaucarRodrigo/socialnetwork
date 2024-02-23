<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagespreproject extends Model
{
    use HasFactory;
    protected $fillable=[
        'description',
        'image',
        'preproject_id'
    ];

    public function preproject() {
        return $this->belongsTo(Preproject::class, 'preproject_id');
    }
}
