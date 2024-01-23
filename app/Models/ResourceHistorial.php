<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceHistorial extends Model
{
    use HasFactory;

    protected $table = 'resource_historial'; 

    protected $fillable = [
        'type',
        'quantity',
        'observation',
        'project_id',
        'resource_id',
    ];

    public function project(){
        return $this->belongsTo(Project::class);
    }

    public function resource(){
        return $this->belongsTo(Resource::class);
    }
}
