<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginMobileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\json;

class ApiController extends Controller
{
    public function login(LoginMobileRequest $request){

        if (Auth::attempt($request->all())) {
            $user = Auth::user(); // Obtener el usuario autenticado
    
            // Generar un token con sanctum (sanctum ya viene preinstalado en laravel)
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

    public function fuel(Request $request){

        
        return response()->json([
            'response' => '2'
        ]);
    }
}
