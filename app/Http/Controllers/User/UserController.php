<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index_user(){
        return Inertia::render('Users/Index', [
            'users' => User::paginate()
        ]);
    }

    public function edit($id){
        return Inertia::render('Users/UserEdit', [
            'users' => User::with('role')->find($id),
            'rols' => Role::all()
        ]);
    }

    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required|string|max:255',
            'dni' => 'required|string|max:8',
            'email' => 'required|string|email|max:255',
            'platform' => 'required|string|max:255',
            'rol' => 'required|numeric',
            // 'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'dni' => $request->dni,
            'email' => $request->email,
            'platform' => $request->platform,
            'role_id' => $request->rol,
            // 'password' => Hash::make($request->password),
        ]);

        return to_route('users.index');
    }

    public function delete(Request $request, $id){
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        if (Hash::check($request->password, $user->password)) {
            User::destroy($id);
            return to_route('users.index');
        }
    }

    public function details($id){
        return Inertia::render('Users/UserDetails', [
            'users' => User::with('role')->find($id)
        ]);
    }

}
