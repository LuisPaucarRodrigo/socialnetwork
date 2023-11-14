<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index_user(){
        return Inertia::render('Users/Index', [
            'users' => User::paginate()
        ]);
    }
}
