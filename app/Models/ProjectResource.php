<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectResource extends Model
{
    use HasFactory;
    protected $table = "project_resource";
    protected $fillable = [
        'project_id',
        'resource_id',
        'quantity',
        'observation',
        'total_price'
    ];
    
}
