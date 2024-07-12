<?php

namespace App\Http\Controllers\Cicsa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CicsaController extends Controller
{
    public function index () {
        
        return Inertia::render('Cicsa/CicsaIndex');
    }
}
