<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistCar extends Model
{
    use HasFactory;

    protected $fillable = [
        'reason',
        'user_id',
        'user_name',
        'additionalEmployees',
        'zone',
        'km',
        'plate',
        'circulation',
        'technique',
        'soat',

        'hornState',
        'brakesState',
        'headlightsState',
        'intermitentlightState',
        'indicatorsState',
        'mirrorsState',
        'tiresState',
        'bumpersState',
        'temperatureGauge',
        'oilGauge',
        'fuelGauge',
        'vehicleCleanliness',
        'doorsState',
        'windshieldState',
        'engineState',
        'batteryState',
        'extinguisher',
        'firstAidKit',
        'cones',
        'jack',
        'spareTire',
        'towCable',
        'batteryCable',
        'reflector',
        'emergencyKit',
        'alarm',
        'chocks',
        'ladderHolder',
        'sidePlate',
        'front',
        'leftSide',
        'rightSide',
        'interior',
        'rearLeftTire',
        'rearRightTire',
        'frontRightTire',
        'frontLeftTire',
        'observation',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');    
    }
    
}
