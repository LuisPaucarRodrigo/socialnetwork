<?php

namespace App\Http\Controllers\CarManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CarsController extends Controller
{
    public function index()
    {
        
        return Inertia::render('FleetCar/Index');
    }
}
