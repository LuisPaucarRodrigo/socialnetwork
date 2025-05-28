<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollDetailTaxAndContribution extends Model
{
    use HasFactory;
    protected $fillable = [
        'payroll_detail_id',
        't_a_c_param_id',
        'amount',
    ];
}
