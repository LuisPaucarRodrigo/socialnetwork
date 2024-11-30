<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostLineCenterEmployee extends Model
{
    use HasFactory;
    protected $table = 'cost_line_center_employees';
    protected $fillable = [
        'cost_center_id',
        'employee_id',
    ];
}
