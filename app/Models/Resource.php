<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'type',
        'quantity',
        'description',
        'adquisition_date',
        'current_location',
        'unique_identification',
    ];
    public function projects()
    {
        return $this->belongsToMany(Project::class)
            ->withPivot('id','quantity', 'observation');
    }
    protected $appends = ['state', 'leftover'];
    protected $hidden = ['projects'];

    public function resource_historials(){
        return $this->hasMany(ResourceHistorial::class, 'resource_id');
    }

    public function getStateAttribute()
    {
        $totalQuantityInProjects = $this->projects->sum('pivot.quantity');
        return $this->quantity > $totalQuantityInProjects ? 'Disponible' : 'No Disponible';
    }

    public function getLeftoverAttribute()
    {
        $totalQuantityInProjects = $this->projects->sum('pivot.quantity');
        return $this->quantity - $totalQuantityInProjects;
    }
    public static function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'adquisition_date' => 'required|date',
            'current_location' => 'required|string|max:255',
            'unique_identification' => 'required|string|unique:resources|max:255',
        ];
    }
    public static function updateRules()
    {
        return [
            'name' => 'required|string|max:255',
            'type' => ['sometimes', 'string', 'max:255'],
            'quantity' => ['sometimes', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'adquisition_date' => ['sometimes', 'date'],
            'current_location' => ['sometimes', 'string', 'max:255'],
        ];
    }
}
