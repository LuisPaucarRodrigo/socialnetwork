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
        if (Auth::attempt($request->validated())) {
            $user = Auth::user();
            $token = $user->createToken('MobileAppToken')->plainTextToken;
            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'dni' => $user->dni,
                'token' => $token,
            ]);
        } else {
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
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
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Sesión cerrada exitosamente']);
    }
}
