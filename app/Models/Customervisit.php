<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customervisit extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer',
        'phone',
        'description',
        'address',
        'date',
        'observation',
        'facade'
    ];

    public function preproject() {
        return $this->HasOne(Preproject::class);
    }
}
