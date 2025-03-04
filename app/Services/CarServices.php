<?php

namespace App\Services;

use App\Models\Car;

class CarServices
{
    public function findCar(){
        $cars = Car::with(['user', 'costline', 'car_document.approvel_car_document', 'car_changelogs' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }, 'car_changelogs.car_changelog_items', 'checklist']);
    }
}