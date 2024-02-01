<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComponentOrMaterial extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'quantity',
        'state',
        'adquisition_date',
        'price',
    ];

    protected $appends = ['total_price','leftover'];

    public function getTotalPriceAttribute() {
        return $this->quantity * $this->price;
    }

    public function getLeftoverAttribute()
    {
        $totalQuantityInProjects = $this->projects->sum('pivot.quantity');
        return $this->quantity - $totalQuantityInProjects;
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_componentormaterial')
            ->withPivot('id','quantity', 'observation');
    }
}
