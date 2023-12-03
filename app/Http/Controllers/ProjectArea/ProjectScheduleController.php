<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectScheduleController extends Controller
{
    public function index()
    {
        return Inertia::render('ProjectArea/ProjectSchedule/Schedule');
    }
}
