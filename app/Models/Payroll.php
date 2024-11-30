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

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'payroll_details');
    }

    public function pension()
    {
        return $this->hasMany(Pension::class);
    }

    public function payroll_details()
    {
        return $this->hasMany(PayrollDetail::class);
    }

    public function getTotalAmountAttribute()
    {
        return $this->payroll_details->sum(function ($detail) {
            return $detail->net_pay;
        });
    }
}
