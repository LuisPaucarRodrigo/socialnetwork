<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;

class Recursos_HumanosController extends Controller
{
    public function index_user(){
        return Inertia::render('Users/Index', [
            'users' => User::paginate()
        ]);
    }

    public function index_info_additional(){
        return Inertia::render('UserInformation', [
            'users' => User::all()
        ]);
    }
}
