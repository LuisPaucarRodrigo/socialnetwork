<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginMobileRequest;
use App\Http\Requests\PreprojectRequest\ImageRequest;
use App\Models\Imagespreproject;
use App\Models\Preproject;
use App\Models\Project;
use App\Models\Projectimage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

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

    public function users(Request $request)
    {
        $user = $request->user();
        if ($user) {
            return response()->json($user);
        } else {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }
    }

    //PreProject
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

    public function preprojectimage(ImageRequest $request)
    {
        $data = $request->validated();
        DB::beginTransaction();
        try {
            $image = str_replace('data:image/png;base64,', '', $data['photo']);
            $image = str_replace(' ', '+', $image);
            $imageContent = base64_decode($image);
            $imagename = time() . '.png';
            file_put_contents(public_path('image/imagereportpreproject/') . $imagename, $imageContent);

            Imagespreproject::create([
                'description' => $data['description'],
                'image' => $imagename,
                'preproject_id' => $data['id'],
            ]);
            DB::commit();
            return response()->noContent();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => 'Hubo un problema al procesar la solicitud.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    //Project

    public function project_index()
    {
        $data = Project::all();
        return response()->json([$data]);
    }

    public function project($id)
    {
        $find = Project::find($id);
        return response()->json([$find]);
    }

    public function projectImage(Request $request){
        $validateData = $request->validated();
        DB::beginTransaction();
        try{
            $image = str_replace('data:image/png;base64,', '', $validateData['photo']);
            $image = str_replace(' ', '+', $image);
            $imageContent = base64_decode($image);
            $imagename = time() . '.png';
            file_put_contents(public_path('image/imagereportproject/') . $imagename, $imageContent);
            
            Projectimage::create([
                'description' => $validateData['description'],
                'image' => $imagename,
                'project_id' => $validateData['id']
            ]);
            DB::commit();
            return response()->json([200]);
        }catch(\Exception $e){
            return response()->json([
                'error' => 'Hubo un problema al procesar su solicitud',
                'message' => $e->getMessage()
            ]);
            DB::rollBack();
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
    }
}
