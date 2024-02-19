<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginMobileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\json;

class ApiController extends Controller
{
    public function login(LoginMobileRequest $request) {
        if (Auth::attempt($request->all())) {
            $user = Auth::user(); // Obtener el usuario autenticado
            // Generar un token con sanctum (sanctum ya viene preinstalado en Laravel)
            $token = $user->createToken('Token Name')->plainTextToken;
            // Devolver una respuesta con el token y datos del usuario
            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'dni' => $user->dni,
                'token' => $token,
            ]);
        } else {
            // Autenticación fallida
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }
    }
    
    public function preproject(){
        $data = [
            [
                'customer' => 'Cliente 1',
                'phone' => '123456789',
                'address' => 'Calle 1, Ciudad',
                'datevisit' => '123'
            ],
            [
                'customer' => 'Cliente 2',
                'phone' => '987654321',
                'address' => 'Calle 2, Ciudad',
                'datevisit' => '456'
            ],
            [
                'customer' => 'Cliente 3',
                'phone' => '456123789',
                'address' => 'Calle 3, Ciudad',
                'datevisit' => '789'
            ]
        ];
        
    
        return response()->json($data);
    }
}
