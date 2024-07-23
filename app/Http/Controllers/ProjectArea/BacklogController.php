<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BacklogController extends Controller
{
    public function index() {
        return Inertia::render('ProjectArea/Backlog/Index');
    }
}
