<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest\CreateUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Models\Area;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register',[
            'rols' => Role::where('id', '!=', 1)->get(),
            'areas' => Area::all()
        ]);
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
            'phone' => $request->phone,
            'platform' => $request->platform,
            'role_id' => $request->rol,
            'area_id' => $request->area_id,
            'password' => Hash::make($request->password),
        ]);

        // event(new Registered($user));El event registered lo podria usar para verificar mi cuenta

        // Auth::login($user);

        // return redirect(RouteServiceProvider::HOME);
    }
}
