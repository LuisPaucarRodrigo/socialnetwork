<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreprojectTitle extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'state',
        'preproject_id',
    ];

    public function preproject()
    {
        return $this->belongsTo(Preproject::class,'preproject_id');
    } 

    public function preprojectCodes()
    {
        return $this->hasMany(PreprojectCode::class);
    } 
}
