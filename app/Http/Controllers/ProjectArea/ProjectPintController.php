<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectPintController extends Controller
{
    public function pint_create_project() {
        return Inertia::render('ProjectArea/Preproject/CreateProjectPint');
    }
}
