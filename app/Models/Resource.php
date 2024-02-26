<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;
    protected $fillable = [
        'resource_description_id',
        'type',
        'serial_number',
        'quantity',
        'unit_price',
        'depreciation',
        'conditional_rent',
        'unit_price_depreciation',
        'observations',
        'adquisition_date',
        'current_location',
        'unique_identification',
    ];
    public function resource_description() {
        return $this->belongsTo(ResourceDescription::class, 'resource_description_id');
    }

    public function getDescriptionAttribute() {
        return $this->resource_description()->first()->name;
    }

    public function projects(){
        return $this->belongsToMany(Project::class)
            ->withPivot('id','quantity', 'observation');
    }
    
    protected $appends = ['state', 'leftover', 'total_product_resources', 'description'];
    protected $hidden = ['projects'];

    public function resource_historials(){
        return $this->hasMany(ResourceHistorial::class, 'resource_id');
    }

    public function project_resource(){
        return $this->hasMany(ProjectResource::class,'resource_id');
    }

    public function getTotalProductResourcesAttribute(){
        return $this->project_resource()->get()->sum(function($item){
            return $item->quantity;
        });
    }

    public function getTotalRefundResourceAttribute(){
        return $this->project_resource()->get()->sum(function($item){
            return $item->project_resource_liquidate ? 
                    $item->project_resource_liquidate->refund_quantity: 0;
        });
    }


    public function getStateAttribute(){
        return $this->getLeftoverAttribute() > 0 ? 'Disponible' : 'No Disponible';
    }

    public function getLeftoverAttribute(){
        return $this->quantity - $this->getTotalProductResourcesAttribute() + $this->getTotalRefundResourceAttribute();
    }


    public static function rules()
    {
        return [
            'resource_description_id' => 'required',
            'type' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'observations' => 'nullable|string',
            'adquisition_date' => 'required|date',
            'conditional_rent' => 'required|boolean',
            'current_location' => 'required|string|max:255',
            'unique_identification' => 'required|string|unique:resources|max:255',
        ];
    }
    public static function updateRules()
    {
        return [
            'resource_description_id' => ['required'],
            'type' => ['sometimes','string','max:255'],
            'serial_number' => ['required','string','max:255'],
            'quantity' => ['sometimes','integer', 'min:1'],
            'unit_price' => ['required','numeric','regex:/^\d+(\.\d{1,2})?$/'],
            'depreciation' => ['sometimes','nullable','numeric'],
            'unit_price_depreciation' => ['sometimes','nullable','numeric'],
            'observations' => ['nullable', 'string'],
            'adquisition_date' => ['sometimes', 'date'],
            'conditional_rent' => ['required', 'boolean'],
            'current_location' => ['sometimes', 'string', 'max:255'],
        ];
    }
}
