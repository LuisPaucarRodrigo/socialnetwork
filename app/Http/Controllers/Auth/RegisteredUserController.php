<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest\CreateUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register',['rols' => Role::all()]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(CreateUserRequest $request)
    {   
        User::create([
            'name' => $request->name,
            'dni' => $request->dni,
            'email' => $request->email,
            'platform' => $request->platform,
            'role_id' => $request->rol,
            'password' => Hash::make($request->password),
        ]);

        // event(new Registered($user));El event registered lo podria usar para verificar mi cuenta

        // Auth::login($user);

        // return redirect(RouteServiceProvider::HOME);
    }
}
