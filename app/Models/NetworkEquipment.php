<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NetworkEquipment extends Model
{
    use HasFactory;
    //protected $table = 'network_equipment';
    protected $fillable = [
        'name',
        'model',
        'serie_number',
        'state',
        'adquisition_date',
        'supplier',
        'price',
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_network_equipment')
            ->withPivot('id', 'observation');
    }
}
