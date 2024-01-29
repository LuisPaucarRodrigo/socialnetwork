<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;
    protected $fillable = [
        'description',
        'type',
        'serial_number',
        'quantity',
        'unit_price',
        'depreciation',
        'unit_price_depreciation',
        'observations',
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
            'description' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'observations' => 'nullable|string',
            'adquisition_date' => 'required|date',
            'current_location' => 'required|string|max:255',
            'unique_identification' => 'required|string|unique:resources|max:255',
        ];
    }
    public static function updateRules()
    {
        return [
            'description' => ['required','string','max:255'],
            'type' => ['sometimes','string','max:255'],
            'serial_number' => ['required','string','max:255'],
            'quantity' => ['sometimes','integer', 'min:1'],
            'unit_price' => ['required','numeric','regex:/^\d+(\.\d{1,2})?$/'],
            'depreciation' => ['sometimes','nullable','numeric'],
            'unit_price_depreciation' => ['sometimes','nullable','numeric'],
            'observations' => ['nullable', 'string'],
            'adquisition_date' => ['sometimes', 'date'],
            'current_location' => ['sometimes', 'string', 'max:255'],
        ];
    }
}
