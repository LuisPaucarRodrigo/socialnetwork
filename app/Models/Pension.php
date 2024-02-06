<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pension extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'values',
        'values_seg',
    ];
    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }
}
