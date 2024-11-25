<?php

namespace App\Models;

use FFI;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'month',
        'state'
    ];

    public function payroll_details(){
        return $this->hasOne(PayrollDetail::class);
    }

    public function pension() {
        return $this->hasMany(Pension::class);
    }
}
