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
        'unit_price'
    ];
    protected $appends = [
        'has_liquidation',
    ];


    public function project(){
        return $this->belongsTo(Project::class, 'project_id');
    }


    public function resource(){
        return $this->belongsTo(Resource::class, 'resource_id');
    }


    public function project_resource_liquidate(){
        return $this->hasOne(ProjectResourceLiquidate::class, 'project_resource_id');
    }


    public function getHasLiquidationAttribute(){
        return $this->project_resource_liquidate()->first() ? true : false;
    }
}
