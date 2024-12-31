<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostLineCenterResource extends Model
{
    use HasFactory;
    protected $table = 'cost_line_center_resources';
    protected $fillable = [
        'cost_center_id',
        'resource_entry_id',
    ];
}
