<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest\UpdateUserRequest;
use App\Models\Area;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{   
    public function index_user()
    {   
        return Inertia::render('Users/Index/Index', [
            'user' => User::with(['role', 'employee:id,user_id'])->paginate(20)
        ]);
    }

    public function search(Request $request)
    {
        $searchQuery = $request->searchQuery;
        $platform = $request->platform;
        $user = User::with(['role', 'employee:id,user_id'])->where(function ($query) use ($searchQuery) {
            $query->where('name', 'like', "%$searchQuery%")
                ->orWhere('dni', 'like', "%$searchQuery%");
        });
        if (count($platform) < 3) {
            $user->whereIn('platform', $platform);
        }
        $user = $user->get();
        return response()->json($user, 200);
    }

    public function linkEmployee(User $user)
    {
        $employee = Employee::select('id', 'dni', 'name')
            ->where('dni', $user->dni)->first();
        if ($employee) {
            $employee->update(['user_id' => $user->id]);
            return response()->json($employee, 200);
        }
        return response()->json(
            "El usuario no tiene un empleado con quien vincular",
            404
        );
    }

    public function edit($id)
    {
        return Inertia::render('Users/UserEdit', [
            'users' => User::with('role', 'area')->find($id),
            'rols' => Role::where('id', '!=', 1)->get(),
            'areas' => Area::all()
        ]);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $validatedData = $request->validated();
        $user = User::findOrFail($id);
        $user->update($validatedData);
    }

    public function delete(Request $request, $id)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        if (Hash::check($request->password, $user->password)) {
            User::destroy($id);
            return to_route('users.index');
        }
    }

    public function details($id)
    {
        // $user = User::with('role', 'area')->find($id);
        // return response()->json($user, 200);
        return Inertia::render('Users/UserDetails', [
            'users' => User::with('role', 'area')->find($id)
        ]);
    }
}
