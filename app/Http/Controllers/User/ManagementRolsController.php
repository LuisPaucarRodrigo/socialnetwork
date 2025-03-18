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
            'rols' => Role::with('permissions')->paginate(),
            'permissions' => Permission::all()
        ]);
    }

    public function store(CreateRolRequest $request)
    {   
        $validateData = $request->validated();
        $role = Role::create([
            'name' => $validateData['name'],
            'description' => $validateData['description'],
        ]);
        $role->permissions()->attach($validateData['permission']);
    }

    public function update(CreateRolRequest $request,$rol_id)
    {   
        $validateData = $request->validated();
        $role = Role::find($rol_id);
        $role->update([
            'name' => $validateData['name'],
            'description' => $validateData['description'],
        ]);
        $role->permissions()->sync($validateData['permission']);
    }

    public function delete($id)
    {
        Role::destroy($id);
        return to_route('rols.index');
    }

    public function details($id)
    {
        $role = Role::with('permissions')->find($id);
        return Inertia::render('Rols/RolDetails', ['rols' => $role]);
    }
}
