<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagespreproject extends Model
{
    use HasFactory;
    protected $fillable=[
        'description',
        'observation',
        'state',
        'image',
        'lat',
        'lon',
        'preproject_code_id'
    ];

    public function preproject_code() {
        return $this->belongsTo(PreprojectCode::class, 'preproject_code_id');
    }
}
