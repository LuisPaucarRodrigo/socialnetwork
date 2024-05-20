<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preproject extends Model
{
    use HasFactory;

    protected $table = 'preproject_user';
    protected $fillable = [
        'preproject_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function preproject()
    {
        return $this->belongsTo(Preproject::class, 'preproject_id');
    }
}
