<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostLine extends Model
{
    use HasFactory;
    protected $table = 'cost_lines';
    protected $fillable = [
        'name',
        'cost_line_id',
    ];
}
