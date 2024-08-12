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
        'personal_2',
        'zone',
        'plate',
        'circulation_doc',
        'technical_doc',
        'soat',
        'horn',
        'brakes',
        'highlowlights',
        'flashing',
        'directional',
        'mirrors',
        'tires',
        'bumper',
        'temperature',
        'oil',
        'fuel',
        'cleanstate',
        'doors',
        'windshield',
        'engine',
        'battery',
        'extinguisher',
        'medical_kit',
        'cones',
        'car_jack',
        'tire',
        'trailer_cable',
        'battery_cable',
        'reflective',
        'tool_kit',
        'alarm',
        'gps',
        'chocks',
        'internal_cab',
        'anti_roll_cab',
        'ladder_rack',
        'lateral_plate',
        'photo_left',
        'photo_right',
        'photo_back',
        'photo_front',
        'photo_first_tire',
        'photo_second_tire',
        'photo_third_tire',
        'photo_fourth_tire',
        'observations',
    ];
}
