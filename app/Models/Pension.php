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
        'payroll_id',
    ];

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }
}
