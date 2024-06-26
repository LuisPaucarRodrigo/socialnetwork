<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginMobileRequest;
use App\Http\Requests\PreprojectRequest\ImageRequest;
use App\Models\Imagespreproject;
use App\Models\PreprojectCode;
use App\Models\Project;
use App\Models\Projectimage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function login(LoginMobileRequest $request)
    {
        if (Auth::attempt($request->validated())) {
            $user = Auth::user();
            if ($request->hasMobileAccess($user)) {
                $token = $user->createToken('MobileAppToken')->plainTextToken;
                return response()->json([
                    'id' => $user->id,
                    'token' => $token,
                ]);
            } else {
                return response()->json(['error' => 'La cuenta no tiene permiso Movil'], 401);
            }
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
    public function preproject(Request $request)
    {
        $user = $request->user();
        $preprojects = $user->preprojects()->where('status', null)->get();
        $data = [];
        foreach ($preprojects as $preproject) {
            if (!$preproject->preproject_code_approve){
                $data[] = [
                    'id' => $preproject->id,
                    'code' => $preproject->code,
                    'description' => $preproject->description,
                    'date' => $preproject->date,
                    'observation' => $preproject->observation,
                ];
            }
        }
        
        return response()->json($data);
    }

    public function preprojectcodephoto($id)
    {
        $preproject = PreprojectCode::with('code')->where('preproject_id', $id)->get();
        $codesWithStatus = [];
        foreach ($preproject as $preprojectCode) {
            $code = $preprojectCode->code;
            $codesWithStatus[] = [
                'id' => $preprojectCode->id,
                'code' => $code->code,
                'status' => $preprojectCode->status ?? $preprojectCode->replaceable_status
            ];
        }
        return response()->json($codesWithStatus);
    }

    public function codephotospecific($id)
    {   
        $data = PreprojectCode::with('code', 'preproject')->find($id);
        $codesWith = [
            'id' => $data->id,
            'codePreproject' => $data->preproject->code,
            'code' => $data->code->code,
            'description' => $data->code->description,
            'status' => $data->status ?? $data->replaceable_status
        ];
        return response()->json($codesWith);
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
                'lat' => $data['latitude'],
                'lon' => $data['longitude'],
                'preproject_code_id' => $data['id'],
            ]);
            DB::commit();
            return response()->json([], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function registerPhoto($id)
    {
        $data = Imagespreproject::where("preproject_code_id", $id)->get();
        $data->each(function ($url) {
            $url->image = url('/image/imagereportpreproject/' . $url->image);
        });
        return response()->json($data);
    }

    //Project

    public function project_index()
    {
        try {
            $data = Project::all();
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function project_show($id)
    {
        try {
            $find = Project::find($id);
            return response()->json($find);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
            DB::rollBack();
        }
    }

    public function project_store_image(ImageRequest $request)
    {
        $validateData = $request->validated();
        DB::beginTransaction();
        try {
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
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
    }
}
