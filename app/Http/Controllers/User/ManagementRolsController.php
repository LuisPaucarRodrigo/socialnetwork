<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\RolRequest\CreateRolRequest;
use App\Models\Module;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ManagementRolsController extends Controller
{
    public function rols_index()
    {
        return Inertia::render('Rols/Rol', [
            'permissions' => [],
            'modules' => Module::with('submodules.functionalities')->where('type', 'module')->get()
        ]);
    }

    public function getRols()
    {
        $roles = Role::where('id', '!=', 1)->with('functionalities')->paginate();
        return response()->json($roles, 200);
    }

    public function store(CreateRolRequest $request)
    {
        $validateData = $request->validated();
        $role = Role::create([
            'name' => $validateData['name'],
            'description' => $validateData['description'],
        ]);
        $role->functionalities()->attach($validateData['functionalities']);
    }

    public function update(CreateRolRequest $request, $rol_id)
    {
        $validateData = $request->validated();
        $role = Role::find($rol_id);
        $role->update([
            'name' => $validateData['name'],
            'description' => $validateData['description'],
        ]);
        $role->functionalities()->sync($validateData['functionalities']);
    }

    public function delete($id)
    {
        Role::destroy($id);
        return response()->json([], 200);
    }

    public function details($id)
    {
        $role = Role::where('id', '!=', 1)->with('functionalities')->find($id);
        return response()->json($role, 200);
        // return Inertia::render('Rols/RolDetails', ['rols' => $role]);
    }
}
