<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginMobileRequest;
use App\Models\Imagespreproject;
use App\Models\Preproject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\json;

class ApiController extends Controller
{
    public function login(LoginMobileRequest $request)
    {
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

    public function preproject()
    {
        $data = Preproject::all();

        return response()->json($data);
    }

    public function preprojectespecific($id)
    {
        $data = Preproject::find($id);
        $data->facade = url('image/facades/' . $data->facade);

        return response()->json($data);
    }

    public function preprojectimage(Request $request)
    {
        $image = str_replace('data:image/png;base64,', '', $request->photo);
        $image = str_replace(' ', '+', $image);
        $imageContent = base64_decode($image);
        $imagename = time() . '.png';
        file_put_contents(public_path('image/imagereportpreproject/') . $imagename, $imageContent);

        Imagespreproject::create([
            'description' => $request->description,
            'image' => $imagename,
            'preproject_id' => $request->id,
        ]);

        $data = Preproject::find("1");
        $data->facade = url('image/facades/' . $data->facade);

        return response()->json($data);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->tokens()->delete(); // Elimina todos los tokens del usuario
        // O si deseas revocar solo el token actual:
        // $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada exitosamente']);
    }
}
