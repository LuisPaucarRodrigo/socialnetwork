<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ManagementRolsController extends Controller
{
    public function rols_index(){
        return Inertia::render('Rols/Rol', [
            'rols' => Role::paginate() , 'permissions' => Permission::all()
        ]);
    }

    public function create () {
        return Inertia::render('Rols/RolCreate',['permission' => Permission::all()]);
    }

    public function store (Request $request) {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'permission' => 'required|array', 
        ]);

        $role = Role::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        if ($request->has('permission')) {
            $role->permissions()->attach($request->permission);
        }

        return to_route('rols.index');
    }

    public function edit ($id) {
        //dd(Role::with('permissions')->find($id), Permission::all());
        return Inertia::render('Rols/RolEdit',['rols' => Role::with('permissions')->find($id),'permissions' => Permission::all()]);
    }

    public function update (Request $request,$id) {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'permission' => 'required|array', 
        ]);
        return to_route('rols.index');
    }

    public function delete ($id) {
        Role::destroy($id);
        return to_route('rols.index');
    }

    public function details ($id) {
        //dd(Role::with('permissions')->find($id));
        return Inertia::render('Rols/RolDetails',['rols' => Role::with('permissions')->find($id)]);
    }
}
