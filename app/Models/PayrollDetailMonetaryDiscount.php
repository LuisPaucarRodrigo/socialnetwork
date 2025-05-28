<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollDetailMonetaryDiscount extends Model
{
    use HasFactory;
    protected $fillable = [ 
        'payroll_detail_id',
        'discount_param_id',
        'amount', 
    ];
}
