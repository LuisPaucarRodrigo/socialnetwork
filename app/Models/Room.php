<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';

    protected $fillable = [
        'rental_type',
        'observations',
        'photo',
        'provider_id',
        'cost_line_id',
    ];

    public function user()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

    public function costline()
    {
        return $this->belongsTo(CostLine::class, 'cost_line_id');
    }

    public function room_document()
    {
        return $this->hasOne(RoomDocument::class, 'room_id');
    }

    public function room_changelogs()
    {
        return $this->hasMany(RoomChangelog::class, 'room_id');
    }

    // public function checklist()
    // {
    //     return $this->hasMany(ChecklistCar::class, 'car_id');
    // }

}
