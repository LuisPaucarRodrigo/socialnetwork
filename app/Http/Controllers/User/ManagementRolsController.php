<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\RolRequest\CreateRolRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ManagementRolsController extends Controller
{
    public function rols_index()
    {
        return Inertia::render('Rols/Rol', [
            'rols' => Role::with('permissions')->paginate(), 'permissions' => Permission::where('name','!=','UserManager')->get()
        ]);
    }

    public function store(CreateRolRequest $request)
    {   
        $role = Role::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        $role->permissions()->attach($request->permission);
    }

    public function update(Request $request,$rol_id)
    {   
        $role = Role::find($rol_id);
        $role->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        $role->permissions()->sync($request->permission);
    }

    public function delete($id)
    {
        Role::destroy($id);
        return to_route('rols.index');
    }

    public function details($id)
    {
        //dd(Role::with('permissions')->find($id));
        return Inertia::render('Rols/RolDetails', ['rols' => Role::with('permissions')->find($id)]);
    }
}
