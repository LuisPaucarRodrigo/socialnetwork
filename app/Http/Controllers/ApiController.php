<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginMobileRequest;
use App\Http\Requests\PextProjectRequest\ApiStoreExpensesRequest;
use App\Http\Requests\PreprojectRequest\ImageRequest;
use App\Models\CicsaAssignation;
use App\Models\HuaweiAdditionalCost;
use App\Models\HuaweiCode;
use App\Models\HuaweiProject;
use App\Models\HuaweiProjectCode;
use App\Models\HuaweiProjectImage;
use App\Models\HuaweiProjectStage;
use App\Models\Imagespreproject;
use App\Models\PreprojectCode;
use App\Models\PreprojectTitle;
use App\Models\HuaweiSite;
use App\Models\PextProject;
use App\Models\PextProjectExpense;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
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
        try {
            $user = User::select('id', 'name', 'dni', 'email')->find($id);
            if ($user) {
                return response()->json($user, 200);
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

    public function cicsaProcess($zone)
    {
        // $cicsaProcess = CicsaAssignation::select('id', 'project_name', 'zone', 'zone2')
        //     ->where(function ($query) use ($zone) {
        //         $query->where('zone', $zone)
        //             ->orWhere('zone2', $zone);
        //     })
        // ->whereHas('cicsa_charge_area', function ($subQuery) {
        //     $subQuery->where(function ($subQuery) {
        //         $subQuery->whereNull('invoice_number')
        //             ->orWhereNull('invoice_date')
        //             ->orWhereNull('amount');
        //     })
        //         ->whereNull('deposit_date');
        // })
        //     ->get();
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();
        $cicsaProcess = Project::where('cost_line_id', 2)
            ->where(function ($query) use ($currentMonthStart, $currentMonthEnd) {
                $query->whereHas('cost_center', function ($subQuery) use ($currentMonthStart, $currentMonthEnd) {
                    $subQuery->where('name', 'like', '%Mantto%')
                        ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd]);
                })
                    ->orWhereHas('cost_center', function ($subQuery) {
                        $subQuery->where('name', 'not like', '%Mantto%');
                    });
            })
            ->whereHas('cicsa_assignation', function ($query) use ($zone) {
                $query->select('id', 'project_name', 'zone', 'zone2')
                    ->where(function ($query) use ($zone) {
                        $query->where('zone', $zone)
                            ->orWhere('zone2', $zone);
                    })
                    ->whereHas('cicsa_charge_area', function ($subQuery) {
                        $subQuery->where(function ($subQuery) {
                            $subQuery->whereNull('invoice_number')
                                ->orWhereNull('invoice_date')
                                ->orWhereNull('amount');
                        })
                            ->whereNull('deposit_date');
                    });
            })
            ->get();
        // ->where(function ($query) use ($currentMonthStart, $currentMonthEnd) {
        //     $query->whereDoesntHave('preproject')
        //         ->orWhereHas('preproject', function ($subQuery) use ($currentMonthStart, $currentMonthEnd) {
        //             $subQuery->whereHas('cost_center', function ($costCenterQuery) {
        //                 $costCenterQuery->where('name', 'not like', '%Mantto%');
        //             })
        //                 ->whereBetween('date', [$currentMonthStart, $currentMonthEnd]);
        //         });
        // });
        $cicsaProcess->cicsa_assignation->each->setAppends([]);
        return response()->json($cicsaProcess->cicsa_assignation, 200);
    }

    public function storeExpensesPext(ApiStoreExpensesRequest $request)
    {
        $validateData = $request->validated();
        try {
            $doc_date = Carbon::createFromFormat('d/m/Y', $validateData['doc_date'])->format('Y-m');

            $pextProject = PextProject::where('date', $doc_date)
                ->select('id')
                ->first();
            if (!$pextProject) {
                return response()->json([
                    'error' => "No se encontraron proyectos pext al cual asignar su gasto."
                ], 404);
            }
            $validateData['pext_project_id'] = $pextProject->id;
            $docDate = Carbon::createFromFormat('d/m/Y', $validateData['doc_date']);
            $validateData['doc_date'] = $docDate->format('Y-m-d');

            if (($validateData['zone'] !== 'MDD') && $validateData['type_doc'] === 'Factura') {
                $validateData['igv'] = 18;
            }
            $user = Auth::user();
            $newDesc = $user->name . ", " . $validateData['description'];
            $validateData['description'] = $newDesc;
            if ($validateData['photo']) {
                $validateData['photo'] = $this->storeBase64Image($validateData['photo'], 'documents/expensesPext', 'Gasto Pext');
            }

            $validateData['user_id'] = $user->id;
            PextProjectExpense::create($validateData);
            return response()->noContent();
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function storeBase64Image($photo, $path, $name)
    {
        try {
            $image = str_replace('data:image/png;base64,', '', $photo);
            $image = str_replace(' ', '+', $image);
            $imageContent = base64_decode($image);
            $imagename = time() . $name . '.png';
            file_put_contents(public_path($path) . "/" . $imagename, $imageContent);
            return $imagename;
        } catch (Exception $e) {
            abort(500, 'something went wrong');
        }
    }

    public function historyExpensesPext()
    {
        $user = Auth::user();
        $month = now()->format('Y-m');
        $expensesPext = PextProjectExpense::where('user_id', $user->id)
            ->whereHas('pext_project', function ($query) use ($month) {
                $query->where('date', $month);
            })
            ->get();
        return response()->json($expensesPext, 200);
    }

    //expenses_dus
    public function fetchSites (Request $request)
    {
        $request->validate([
            'macro_project' => 'required'
        ]);

        $projects = HuaweiProject::where('macro_project', $request->macro_project)->get();

        $sites = $projects->flatMap(function ($project) {
            return $project->huawei_site()->get()->map(function ($site) {
                return [
                    'id' => $site->id,
                    'name' => $site->name,
                ];
            });
        })->unique('id');

        return response()->json($sites, 200);
    }

    public function fetchProjects (Request $request)
    {
        $request->validate([
            'macro_project' => 'required',
            'site' => 'required'
        ]);
        $projects = HuaweiProject::select('id', 'name', 'assigned_diu')
            ->where('macro_project', $request->macro_project)
            ->where('huawei_site_id', $request->site)
            ->get()
            ->makeHidden([
                'code',
                'additional_cost_total',
                'static_cost_total',
                'materials_in_project',
                'equipments_in_project',
                'materials_liquidated',
                'equipments_liquidated',
                'total_earnings',
                'total_real_earnings',
                'total_real_earnings_without_deposit',
                'total_project_cost',
                'total_employee_costs',
                'total_essalud_employee_cost',
                'huawei_project_resources'
            ])
            ->filter(function ($project){
                return $project->state == 1;
            });

        return response()->json($projects, 200);
    }

    public function storeExpense ($huawei_project, Request $request)
    {
        $data = $request->validate([
            'id' => 'required|numeric',
            'expense_type' => 'required',
            'employee' => 'required',
            'cdp_type' => 'required',
            'doc_number' => 'required',
            'op_number' => 'required',
            'ruc' => 'required',
            'description' => 'required',
            'amount' => 'required',
            'image1' => 'required',
            'image2' => 'nullable',
            'image3' => 'nullable',
        ]);

        $data['expense_date'] = Carbon::now();
        $data['huawei_project_id'] = $huawei_project;
        $data['refund_status'] = 'PENDIENTE';

        DB::beginTransaction();

        $new_expense = HuaweiAdditionalCost::create([
            'expense_type' => $data['expense_type'],
            'employee' => $data['employee'],
            'expense_date' => $data['expense_date'],
            'cdp_type' => $data['cdp_type'],
            'doc_number' => $data['doc_number'],
            'op_number' => $data['op_number'],
            'ruc' => $data['ruc'],
            'description' => $data['description'],
            'amount' => $data['amount'],
            'refund_status' => $data['refund_status'],
            'huawei_project_id' => $data['huawei_project_id']
        ]);

        try {
            $expenseDirectory = 'documents/huawei/monthly_expenses/';
            $imageFields = ['image1', 'image2', 'image3'];
            $imageUpdates = [];

            foreach ($imageFields as $index => $field){
                if (isset($data[$field])){
                    $image = str_replace('data:image/png;base64,', '', $data[$field]);
                    $image = str_replace(' ', '+', $image);
                    $imageContent = base64_decode($image);
                    $data[$field] = time() . '.png';
                    file_put_contents(public_path($expenseDirectory) . $data[$field], $imageContent);
                    $imageUpdates[$field] = $data[$field];
                }
            }
            $new_expense->update($imageUpdates);
            DB::commit();
            return response()->json([201]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
