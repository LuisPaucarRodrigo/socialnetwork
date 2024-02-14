<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preproject extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'unit_type',
        'cost',
        'customervisit_id',
    ];

    public function customervisit()
    {
        return $this->belongsTo(Employee::class, 'customervisit_id');
    }

    public function preproject() {
        return $this->HasOne(Project::class);
    }
}
