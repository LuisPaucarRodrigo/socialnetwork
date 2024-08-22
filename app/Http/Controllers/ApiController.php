<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChecklistRequest\ChecklistDailytoolkitRequest;
use App\Http\Requests\LoginMobileRequest;
use App\Http\Requests\PreprojectRequest\ImageRequest;
use App\Models\ChecklistDailytoolkit;
use App\Models\Imagespreproject;
use App\Models\Preproject;
use App\Models\PreprojectCode;
use App\Models\PreReportHuaweiGeneral;
use App\Models\Project;
use App\Models\Projectimage;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{

    protected $main_directory;

    public function __construct()
    {
        $this->main_directory = 'LocalDrive';
    }

    public function login(LoginMobileRequest $request)
    {
        if (Auth::attempt($request->validated())) {
            $user = Auth::user();
            if ($request->hasMobileAccess($user)) {

                // // Buscar si el usuario ya tiene un token
                // $existingToken = $user->tokens()->first();

                // // Si el usuario ya tiene un token, devolver el token existente
                // if ($existingToken) {
                //     return response()->json([
                //         'id' => $user->id,
                //         'token' => $existingToken->plainTextToken,
                //     ]);
                // }

                $token = $user->createToken('MobileAppToken')->plainTextToken;
                return response()->json([
                    'id' => $user->id,
                    'token' => $token,
                ]);
            } else {
                return response()->json(['error' => 'Usuario no Autorizado'], 401);
            }
        } else {
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
        }
    }

    public function users($id)
    {
        $user = User::select('name', 'dni', 'email')->find($id);
        if ($user) {
            return response()->json($user);
        } else {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }
    }

    //PreProject
    public function preproject($id)
    {
        $user = User::find($id);
        $preprojects = $user->preprojects()
            ->where('status', null)->get();
        $data = [];
        foreach ($preprojects as $preproject) {
            if (!$preproject->preproject_code_approve) {
                $data[] = [
                    'id' => $preproject->id,
                    'code' => $preproject->code,
                    'description' => $preproject->description,
                    'date' => $preproject->date,
                    'observation' => $preproject->observation,
                ];
            }
        }

        return response()->json($preprojects);
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

    public function indexHuaweiProjectGeneral()
    {
        $data = PreReportHuaweiGeneral::all();
        return response()->json(['message' => 'hola'], 201);
    }

    public function storeHuaweiProjectGeneral(Request $request)
    {
        $validateData = $request->validate([
            'site' => 'required',
            'elaborated' => 'required',
            'code' => 'required',
            'name' => 'required',
            'address' => 'required',
            'reference' => 'required',
            'access' => 'required',
        ]);
        PreReportHuaweiGeneral::create($validateData);
        return response()->json([], 200);
    }

    public function localDriveIndex(Request $request)
    {
        $root = $request->input('root');
        $path = $request->input('path');
        try {
            $previousPath = '';
            if (!$root) {
                $lastSlashPosition = strrpos($path, '/');
                if ($lastSlashPosition !== false) {
                    $previousPath = substr($path, 0, $lastSlashPosition) !== $this->main_directory
                        ? substr($path, 0, $lastSlashPosition)
                        : '';
                }
            }
            $currentPath = $root ? $this->main_directory : $path;
            $folders_archives = $this->scanFolder(storage_path('app/' . $currentPath));
            return response()->json([
                'folders_archives' => $folders_archives,
                'currentPath' => $currentPath,
                'previousPath' => $previousPath,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al procesar la solicitud',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function scanFolder($folderPath)
    {
        $folders = [];
        $files = [];
        $contents = scandir($folderPath);
        foreach ($contents as $item) {
            if ($item[0] !== '.') {
                $itemPath = $folderPath . '/' . $item;
                if (is_dir($itemPath)) {
                    $folders[] = [
                        'name' => $item,
                        'type' => 'folder',
                        'path' => str_replace(storage_path('app/'), '', $itemPath),
                        'size' => '',
                    ];
                } else {
                    $files[] = [
                        'name' => $item,
                        'type' => 'archive',
                        'path' => str_replace(storage_path('app/'), '', $itemPath),
                        'size' => round(filesize($itemPath) / 1024, 2) . " KB",
                    ];
                }
            }
        }
        return array_merge($folders, $files);
    }

    public function localDriveDownload(Request $request)
    {
        $path = $request->input('path');
        $storagePath = storage_path('app/' . $path);

        if (is_file($storagePath)) {
            ob_end_clean();
            return $this->downloadFile($path);
        } else {
            return abort(404, 'El recurso solicitado no existe.');
        }
    }

    private function downloadFile($path)
    {
        $filePath = storage_path('app/' . $path);

        if (!file_exists($filePath)) {
            return abort(404, 'El archivo no existe.');
        }
        return response()->download($filePath);
    }

    public function dailytoolkit_store(ChecklistDailytoolkitRequest $request)
    {
        $data = $request->validated();
        DB::beginTransaction();
        try {
            $data['user_id'] = Auth::user()->id;
            ChecklistDailytoolkit::create($data);
            DB::commit();
            return response()->json([], 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al procesar la solicitud',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
