<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChecklistRequest\ChecklistDailytoolkitRequest;
use App\Http\Requests\LoginMobileRequest;
use App\Http\Requests\PreprojectRequest\ImageRequest;
use App\Models\ChecklistDailytoolkit;
use App\Models\HuaweiCode;
use App\Models\HuaweiProject;
use App\Models\HuaweiProjectCode;
use App\Models\HuaweiProjectImage;
use App\Models\HuaweiProjectStage;
use App\Models\Imagespreproject;
use App\Models\Preproject;
use App\Models\PreprojectCode;
use App\Models\PreprojectTitle;
use App\Models\PreReportHuaweiGeneral;
use App\Models\Project;
use App\Models\HuaweiSite;
use App\Models\Projectimage;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\map;

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
        try {
			$user = User::select('id','name', 'dni', 'email')->find($id);
			if ($user) {
				return response()->json($user,200);
			}
		} catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    //PreProject
    public function preproject($id)
    {
        try {
            $user = User::select('id')->find($id);
            $preprojects = $user->preprojects()
                ->select('preprojects.id as preproject_id', 'preproject_user.id as pivot_id', 'code', 'description', 'date', 'observation', 'status')
                ->whereNull('status')
                ->whereHas('preprojectTitles', function ($query) {
                    $query->where('state', 1);
                })
                ->get();
            
            return response()->json($preprojects);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function preprojectcodephoto($id)
    {
        try {
            $preprojectTitle = PreprojectTitle::with(['preprojectCodes.code' => function ($query) {
                $query->select('id', 'code');
            }, 'preprojectCodes' => function ($query) {
                $query->select('id', 'preproject_title_id', 'code_id', 'status');
            }])
                ->whereNotNull('state')->where('preproject_id', $id)
                ->select('id', 'type')->get();
            return response()->json($preprojectTitle);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function codephotospecific($id)
    {
        $data = PreprojectCode::with(['code' => function ($query) {
            $query->select('id', 'code', 'description')->with('code_images');
        }, 'preprojectTitle' => function ($query) {
            $query->select('id', 'preproject_id');
            $query->with(['preproject' => function ($query) {
                $query->select('id', 'code');
            }]);
        }])->select('id', 'preproject_title_id', 'code_id')->find($id);
        $images = $data->code->code_images->map(function ($image) {
            $image->image = url('/image/imageCode/' . $image->image);
            return $image;
        });
        $codesWith = [
            'id' => $data->id,
            'codePreproject' => $data->preprojectTitle->preproject->code,
            'code' => $data->code->code,
            'description' => $data->code->description,
            'status' => $data->status ?? $data->replaceable_status,
            'images' => $images
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
            $data['image'] = time() . '.png';
            file_put_contents(public_path('image/imagereportpreproject/') . $data['image'], $imageContent);

            Imagespreproject::create([
                'description' => $data['description'],
                'image' => $data['image'],
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

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
    }

    //huawei
    public function indexHuaweiProjectGeneral()
    {
        $projects = HuaweiProject::where('status', 1)->with(['huawei_site' => function ($query) {
            $query->select('id', 'name'); // Selecciona campos específicos del modelo relacionado
        }])->select('id', 'assigned_diu', 'huawei_site_id')->get()
            ->makeHidden([
                'total_earnings',
                'total_real_earnings',
                'total_real_earnings_without_deposit',
                'total_project_cost',
                'total_employee_costs',
                'total_essalud_employee_cost',
                'additional_cost_total',
                'static_cost_total',
                'materials_in_project',
                'equipments_in_project',
                'materials_liquidated',
                'equipments_liquidated',
                'huawei_project_resources',
                'state'
            ]);;

        return response()->json($projects, 201);
    }

    public function storeHuaweiProjectGeneral(Request $request)
    {
        $request->validate([
            'site' => 'required',
            'diu' => 'required',
        ]);

        $inputSiteName = $request->input('site');

        $maxSimilarity = 0;
        $bestMatch = null;
        $siteToUse = null;
        // Retrieve all site names
        $sites = HuaweiSite::all()->pluck('name')->toArray();

        // Iterate through sites to find the best match
        foreach ($sites as $site) {
            similar_text(strtolower($inputSiteName), strtolower($site), $similarity);

            if ($similarity > 70 && $similarity > $maxSimilarity) {
                $maxSimilarity = $similarity;
                $bestMatch = $site;
            }
        }

        if ($bestMatch) {
            // Found a similar site
            $siteToUse = HuaweiSite::where('name', $bestMatch)->first();
        } else {
            // No similar site found, create a new one
            $siteToUse = HuaweiSite::create([
                'name' => $this->sanitizeText($inputSiteName),
            ]);
        }

        HuaweiProject::create([
            'name' => $request->diu,
            'assigned_diu' => $request->diu,
            'huawei_site_id' => $siteToUse->id,
            'status' => 1,
        ]);

        return response()->json([], 200);
    }

    private function sanitizeText($text)
    {
        // Convertir a mayúsculas
        $sanitizedText = strtoupper($text);

        // Reemplazar guiones y subguiones por espacios
        $sanitizedText = str_replace(['-', '_'], ' ', $sanitizedText);

        return $sanitizedText;
    }


    public function getStagesPerProject(HuaweiProject $huawei_project)
    {
        $stages = HuaweiProjectStage::where('huawei_project_id', $huawei_project->id)
            ->where('status', 1)
            ->with([
                'huawei_project_codes' => function ($query) {
                    $query->select('id', 'huawei_project_stage_id', 'huawei_code_id', 'status')
                        ->with([
                            'huawei_code' => function ($query) {
                                $query->select('id', 'code');
                            }
                        ]);
                },
                'huawei_project_codes.huawei_code' => function ($query) {
                    $query->select('id', 'code');
                }
            ])
            ->select('id', 'description')
            ->get();

        // Ocultar los `huawei_project_images` en cada `huawei_project_code`
        $stages->each(function ($stage) {
            $stage->huawei_project_codes->each(function ($code) {
                $code->makeHidden(['huawei_project_images']);
            });
        });

        return response()->json(['stages' => $stages], 200);
    }


    public function storeImagePerCode(HuaweiProjectCode $code, Request $request)

    {
        $data = $request->validate([
            'id' => 'required|numeric',
            'photo' => 'required',
            'description' => 'nullable',
            'latitude' => 'required',
            'longitude' => 'required',
            'site' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $image = str_replace('data:image/png;base64,', '', $data['photo']);
            $image = str_replace(' ', '+', $image);
            $imageContent = base64_decode($image);
            $data['photo'] = time() . '.png';
            file_put_contents(public_path('documents/huawei/photoreports/') . $data['photo'], $imageContent);

            HuaweiProjectImage::create([
                'description' => $data['description'],
                'image' => $data['photo'],
                'lat' => $data['latitude'],
                'lon' => $data['longitude'],
                'site' => $data['site'],
                'huawei_project_code_id' => $data['id'],
            ]);
            DB::commit();
            return response()->json([201]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getImageHistoryPerCode(HuaweiProjectCode $code)
    {
        $images = HuaweiProjectImage::where('huawei_project_code_id', $code->id)
            ->select('id', 'huawei_project_code_id', 'image', 'description', 'observation', 'lat', 'lon', 'state')
            ->get()
            ->map(function ($image) {
                $image->image = asset('documents/huawei/photoreports/' . $image->image);
                return $image;
            });

        return response()->json(['images' => $images], 200);
    }

    public function getCodesAndProjectCode($code)
    {
        $project_code = HuaweiProjectCode::where('id', $code)
            ->select('id', 'status', 'huawei_code_id', 'huawei_project_stage_id')
            ->with([
                'huawei_project_stage' => function ($query) {
                    $query->select('id', 'huawei_project_id');
                },
                'huawei_project_stage.huawei_project' => function ($query) {
                    $query->select('id');
                }
            ])
            ->first()
            ->makeHidden(['huawei_project_images']);

        $project_code->huawei_project_stage->huawei_project->makeHidden([
            'additional_cost_total',
            'static_cost_total',
            'state',
            'materials_in_project',
            'equipments_in_project',
            'materials_liquidated',
            'equipments_liquidated',
            'total_earnings',
            'total_real_earnings',
            'total_real_earnings_without_deposit',
            'total_project_cost',
            'total_employee_costs',
            'total_essalud_employee_cost'
        ]);

        $found_code = HuaweiCode::where('id', $project_code->huawei_code_id)
            ->select('id', 'code', 'description')
            ->first();

        $data = [
            'id' => $code,
            'scenario' => $found_code->code,
            'scenario_description' => $found_code->description,
            'project_code' => $project_code->huawei_project_stage->huawei_project->code,
            'project_code_state' => $project_code->state
        ];

        return response()->json($data);
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

    public function getSites()
    {
        return response()->json(['sites' => HuaweiSite::select('id', 'name')->get()], 200);
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
