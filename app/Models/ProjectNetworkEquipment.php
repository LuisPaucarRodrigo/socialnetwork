<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectNetworkEquipment extends Model
{
    use HasFactory;

    protected $table = "project_network_equipment";
    protected $fillable = [
        'project_id',
        'network_equipment_id',
        'observation',
    ];
}
