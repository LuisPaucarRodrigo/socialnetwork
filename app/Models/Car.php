<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $table = 'cars';

    protected $fillable = [
        'brand',
        'model',
        'plate',
        'year',
        'type',
        'photo',
        'user_id',
        'cost_line_id',
    ];

    //relations
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function costline()
    {
        return $this->belongsTo(CostLine::class, 'cost_line_id');
    }

    public function car_document()
    {
        return $this->hasOne(CarDocument::class, 'car_id');
    }

    public function car_changelogs()
    {
        return $this->hasMany(CarChangelog::class, 'car_id');
    }

    public function checklist()
    {
        return $this->hasOne(ChecklistCar::class, 'car_id');
    }
}
