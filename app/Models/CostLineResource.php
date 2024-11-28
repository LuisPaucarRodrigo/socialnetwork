<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostLineResource extends Model
{
    use HasFactory;
    protected $table = 'cost_line_resources';
    protected $fillable = [
        'resource_entry_id',
        'cost_line_id',
    ];
}
