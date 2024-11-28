<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostLineEmployee extends Model
{
    use HasFactory;
    protected $table = 'cost_line_employees';
    protected $fillable = [
        'employee_id',
        'cost_line_id',
    ];
}
