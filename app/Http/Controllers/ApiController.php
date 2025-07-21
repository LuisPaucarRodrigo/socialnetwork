<?php

namespace App\Http\Controllers;

use App\Constants\HuaweiConstants;
use App\Constants\PextConstants;
use App\Constants\PintConstants;
use App\Http\Requests\ChecklistRequest\ChecklistCarRequest;
use App\Http\Requests\ChecklistRequest\ChecklistDailytoolkitRequest;
use App\Http\Requests\ChecklistRequest\ChecklistEppRequest;
use App\Http\Requests\ChecklistRequest\ChecklistToolkitRequest;
use App\Http\Requests\CostsRequest\AdditionalCostsApiRequest;
use App\Http\Requests\Huawei\HuaweiMobileRequest;
use App\Http\Requests\LoginMobileRequest;
use App\Http\Requests\PextProjectRequest\ApiStoreExpensesRequest;
use App\Http\Requests\PreprojectRequest\ImageRequest;
use App\Models\AdditionalCost;
use App\Models\Car;
use App\Models\ChecklistCar;
use App\Models\ChecklistDailytoolkit;
use App\Models\ChecklistEpp;
use App\Models\ChecklistToolkit;
use App\Models\CicsaAssignation;
use App\Models\Employee;
use App\Models\HuaweiMonthlyExpense;
use App\Models\HuaweiProject;
use App\Models\Imagespreproject;
use App\Models\PreprojectCode;
use App\Models\PextProjectExpense;
use App\Models\Preproject;
use App\Models\Project;
use App\Models\StaticCost;
use App\Services\ApiServices;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{

    protected $main_directory;
    protected $apiService;

    public function __construct(ApiServices $apiService)
    {
        $this->apiService = $apiService;
        $this->main_directory = 'LocalDrive';
    }

    public function checkVersion(Request $request)
    {
        $clientVersion = $request->header('X-App-Version');
        $currentVersion = '2.6.0';
        if (version_compare($clientVersion, $currentVersion, '<')) {
            return response()->json([
                'update_required' => true,
                'error' => "Tu app está desactualizada. Actualiza a la versión $currentVersion."
            ], 426);
        }
        return response()->json([], 200);
    }

    public function verificationToken()
    {
        return response()->json([], 200);
    }

    public function login(LoginMobileRequest $request)
    {
        if (Auth::attempt($request->validated())) {
            $user = Auth::user();
            if ($request->hasMobileAccess($user)) {

                $employee = Employee::select('id', 'user_id')
                    ->where('user_id', $user->id)
                    ->with(['contract:id,cost_line_id,employee_id'])
                    ->first();
                if ($employee) {
                    $token = $user->createToken('MobileAppToken')->plainTextToken;
                    return response()->json([
                        'id' => $user->id,
                        'token' => $token,
                        'cost_line_id' => $employee->contract->cost_line_id
                    ]);
                } else {
                    return response()->json(['error' => 'Trabajador no registrado'], 422);
                }
            } else {
                return response()->json(['error' => 'Usuario no Autorizado'], 401);
            }
        } else {
            return response()->json(['error' => 'Credenciales incorrectas'], 422);
        }
    }

    public function users($id)
    {
        try {
            $user = $this->apiService->findUser($id);
            return response()->json($user, 200);
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
            $user = $this->apiService->findUser($id);
            $preprojects = $this->apiService->userPreproject($user)->get();
            return response()->json($preprojects, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function preprojectcodephoto($id)
    {
        try {
            $preprojectTitle = $this->apiService->preprojectTitle($id)->get();
            return response()->json($preprojectTitle, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function codephotospecific($id)
    {
        $data = PreprojectCode::select('id', 'preproject_title_id', 'code_id')
            ->with([
                'code:id,code,description',
                'code.code_images',
                'preprojectTitle:id,preproject_id',
                'preprojectTitle.preproject:id,code'
            ])
            ->find($id);

        $images = $data->code->code_images->map(function ($image) {
            $image->image = url('/image/imageCode/' . $image->image);
            return $image;
        });

        return response()->json([
            'id' => $data->id,
            'codePreproject' => $data->preprojectTitle->preproject->code,
            'code' => $data->code->code,
            'description' => $data->code->description,
            'status' => $data->status ?? $data->replaceable_status,
            'images' => $images
        ], 200);
    }

    public function preprojectimage(ImageRequest $request)
    {
        $data = $request->validated();
        DB::beginTransaction();
        try {
            $data['image'] = $this->apiService->storeBase64Image($data['photo'], "image/imagereportpreproject/", null);
            Imagespreproject::create([
                'description' => $data['description'],
                'image' => $data['image'],
                'lat' => $data['latitude'],
                'lon' => $data['longitude'],
                'preproject_code_id' => $data['id'],
            ]);
            DB::commit();
            return response()->json([], 200);
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
        return response()->json($data, 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([], 200);
    }

    //huawei

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

    public function cicsaProcess($zone)
    {
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();

        $cicsaProcess = CicsaAssignation::select('id', 'zone', 'project_id', 'project_name')
            ->where(function ($query) use ($zone) {
                $query->where('zone', $zone)
                    ->orWhere('zone2', $zone)
                    ->orWhere('zone3', $zone);
            });

        $cicsaProcess->whereHas('project', function ($query) use ($currentMonthStart, $currentMonthEnd) {
            $query->where('cost_line_id', 2)
                ->where('is_accepted', 1)
                ->where(function ($subQuery) use ($currentMonthStart, $currentMonthEnd) {
                    $subQuery->where(function ($subSubQuery) use ($currentMonthStart, $currentMonthEnd) {
                        $subSubQuery->whereHas('cost_center', function ($costCenterQuery) {
                            $costCenterQuery->where(function ($e) {
                                $e->where('name', 'like', '%Mantto%')
                                    ->orWhere('name', 'like', '%INDRA%');
                            });
                        })
                            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
                            ->where('initial_budget', '>', 0);
                    })
                        ->orWhereHas('cost_center', function ($costCenterQuery) {
                            $costCenterQuery->where('name', 'not like', '%Mantto%');
                        });
                });
        });

        $cicsaProcess = $cicsaProcess->where(function ($query) {
            $query->whereHas('cicsa_charge_area', function ($subQuery) {
                $subQuery->select('id', 'cicsa_assignation_id', 'invoice_number', 'invoice_date', 'amount', 'deposit_date')
                    ->where(function ($subSubQuery) {
                        $subSubQuery->whereNull('invoice_number')
                            ->orWhereNull('invoice_date')
                            ->orWhereNull('amount');
                    })
                    ->whereNull('deposit_date');
            })
                ->orDoesntHave('cicsa_charge_area');
        })
            ->orderBy('project_name')
            ->get();

        $cicsaProcess->each->setAppends([]);
        return response()->json($cicsaProcess, 200);
    }

    public function storeExpensesPext(ApiStoreExpensesRequest $request)
    {
        $validateData = $request->validated();
        try {
            $validateData = $this->apiService->transformExpenseData($validateData);
            $expense = PextProjectExpense::create($validateData);
            // if ($expense) {
            //     $project = Project::select('description')->find($expense->project_id);
            //     $response = [
            //         'amount' => $expense->amount,
            //         'created_at' => $expense->created_at,
            //         'project' => $project->description,
            //         'zone' => $expense->zone,
            //         'expense_type' => $expense->expense_type,
            //     ];
            //     return response()->json($response, 200);
            // }
            // return response()->json([
            //     'error' => 'Hubo un error al momento de ingresar.Porvafor intentenlo mas tarde.'
            // ], 500);
            return response()->json([], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function historyExpensesPext()
    {
        $expensesPext = $this->apiService->getExpensesPext()->get();
        return response()->json($expensesPext, 200);
    }

    public function employee_cost_line($cost_line_id)
    {
        $employees = $this->apiService->employeesCostLine($cost_line_id)->get();
        return response()->json($employees, 200);
    }

    //fleetcar

    public function index_car($cost_line_id)
    {
        $car = $this->apiService->car($cost_line_id)->get();
        return response()->json($car, 200);
    }

    public function getPintMobileConstants()
    {
        return response()->json([
            'expenseTypes' => PintConstants::mobileExpenses(),
            'docTypes' => PintConstants::mobileDocTypes(),
            'zones' => PintConstants::mobileZones()
        ]);
    }

    public function getPextMobileConstants()
    {
        return response()->json([
            'zones' => PextConstants::getZone(),
            'docTypes' => PextConstants::getDocumentsType(),
            'expenseTypes' => PextConstants::getExpenseType(),
        ]);
    }

    public function contantsCheckList($cost_line_id)
    {
        $employees = $this->apiService->employeesCostLine($cost_line_id)->get();
        $zones = $cost_line_id == 1 ? PintConstants::mobileZones() : PextConstants::getZone();
        $car = $this->apiService->car($cost_line_id)->get();

        return response()->json([
            'zones' => $zones,
            'employees' => $employees,
            'car' => $car,
        ], 200);
    }

    //test

    public function fetchSites($macro)
    {
        $projects = HuaweiProject::where('macro_project', $macro)->get();

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

    public function fetchProjects($macro, $site_id)
    {
        $projects = HuaweiProject::select('id', 'assigned_diu')
            ->where('macro_project', $macro)
            ->where('huawei_site_id', $site_id)
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
            ->filter(function ($project) {
                return $project->state == 1;
            });

        return response()->json($projects, 200);
    }

    public function getHuaweiConstants()
    {
        return response()->json([
            'expenseTypes' => HuaweiConstants::getVariableExpenseTypes(),
            'cdpTypes' => HuaweiConstants::getCDPTypes(),
        ], 200);
    }

    public function getExpensesHistory()
    {
        $user = Auth::user();
        $expenses = HuaweiMonthlyExpense::where('user_id', $user->id)
            ->get()
            ->makeHidden(['huawei_project', 'general_expense']);
        return response()->json($expenses, 200);
    }

    public function storeHuaweiExpense(HuaweiMobileRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();

        $data['expense_date'] = Carbon::now();
        $data['user_id'] = $user->id;
        $data['employee'] = $user->name;
        $data['description'] = $user->name . ',' . $data['description'];
        DB::beginTransaction();
        try {
            $expenseDirectory = 'documents/huawei/monthly_expenses/';
            if (isset($data['image'])) {
                $data['image'] = $this->apiService->storeBase64Image($data['image'], $expenseDirectory, null);
            }
            $expense = HuaweiMonthlyExpense::create($data);
            DB::commit();
            // if ($expense) {
            //     $project = HuaweiProject::select('description')->find($expense->project_id);
            //     $response = [
            //         'amount' => $expense->amount,
            //         'created_at' => $expense->created_at,
            //         'project' => $project->description,
            //         'zone' => $expense->zone,
            //         'expense_type' => $expense->expense_type,
            //     ];
            //     return response()->json($response, 200);
            // }
            // return response()->json([
            //     'error' => 'Hubo un error al momento de ingresar.Porvafor intentenlo mas tarde.'
            // ], 500);
            return response()->json([], 200);
        } catch (\Exception $e) {
            Log::info('Error storing Huawei expense: ' . $e->getMessage());
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function car_store(ChecklistCarRequest $request)
    {
        $data = $request->validated();
        try {
            $data['maintenanceTools'] = $this->storeBase64Image($data['maintenanceTools'], 'image/checklist/checklistcar', 'maintenanceTools');
            $data['preventionTools'] = $this->storeBase64Image($data['preventionTools'], 'image/checklist/checklistcar', 'preventionTools');
            $data['imageSpareTire'] = $this->storeBase64Image($data['imageSpareTire'], 'image/checklist/checklistcar', 'imageSpareTire');
            $data['front'] = $this->storeBase64Image($data['front'], 'image/checklist/checklistcar', 'front');
            $data['leftSide'] = $this->storeBase64Image($data['leftSide'], 'image/checklist/checklistcar', 'leftSide');
            $data['rightSide'] = $this->storeBase64Image($data['rightSide'], 'image/checklist/checklistcar', 'rightSide');
            $data['interior'] = $this->storeBase64Image($data['interior'], 'image/checklist/checklistcar', 'interior');
            $data['rearLeftTire'] = $this->storeBase64Image($data['rearLeftTire'], 'image/checklist/checklistcar', 'rearLeftTire');
            $data['rearRightTire'] = $this->storeBase64Image($data['rearRightTire'], 'image/checklist/checklistcar', 'rearRightTire');
            $data['frontRightTire'] = $this->storeBase64Image($data['frontRightTire'], 'image/checklist/checklistcar', 'frontRightTire');
            $data['frontLeftTire'] = $this->storeBase64Image($data['frontLeftTire'], 'image/checklist/checklistcar', 'frontLeftTire');
            $data['back'] = $this->storeBase64Image($data['back'], 'image/checklist/checklistcar', 'back');
            $data['dashboard'] = $this->storeBase64Image($data['dashboard'], 'image/checklist/checklistcar', 'dashboard');
            $data['rearSeat'] = $this->storeBase64Image($data['rearSeat'], 'image/checklist/checklistcar', 'rearSeat');

            $data['user_id'] = Auth::user()->id;
            $data['user_name'] = Auth::user()->name;
            $car = Car::find($data['car_id']);
            $data['plate'] = $car->plate;
            ChecklistCar::create($data);
            return response()->json([], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function toolkit_store(ChecklistToolkitRequest $request)
    {
        $data = $request->validated();
        try {
            if ($data['badTools']) {
                $data['badTools'] = $this->storeBase64Image($data['badTools'], 'image/checklist/checklisttoolkit', 'badTools');
            }
            if ($data['goodTools']) {
                $data['goodTools'] = $this->storeBase64Image($data['goodTools'], 'image/checklist/checklisttoolkit', 'goodTools');
            }
            $data['user_id'] = Auth::user()->id;
            $data['user_name'] = Auth::user()->name;
            ChecklistToolkit::create($data);
            return response()->json([], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function dailytoolkit_store(ChecklistDailytoolkitRequest $request)
    {
        $data = $request->validated();
        DB::beginTransaction();
        try {
            $data['user_id'] = Auth::user()->id;
            $data['user_name'] = Auth::user()->name;
            ChecklistDailytoolkit::create($data);
            DB::commit();
            return response()->json([], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => 'Ocurrió un error al procesar la solicitud',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function epp_store(ChecklistEppRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;
        $data['user_name'] = Auth::user()->name;
        ChecklistEpp::create($data);
        return response()->json([], 200);
    }

    public function checklist_history()
    {
        $userId = Auth::user()->id;

        $cars = ChecklistCar::where('user_id', $userId)->select('created_at')->get();
        $toolkits = ChecklistToolkit::where('user_id', $userId)->select('created_at')->get();
        $dailytoolkits = ChecklistDailytoolkit::where('user_id', $userId)->select('created_at')->get();
        $epps = ChecklistEpp::where('user_id', $userId)->select('created_at')->get();

        $cars = $cars->map(function ($item) {
            $item->type = 'Vehiculo';
            return $item;
        });

        $toolkits = $toolkits->map(function ($item) {
            $item->type = 'Herramientas';
            return $item;
        });

        $dailytoolkits = $dailytoolkits->map(function ($item) {
            $item->type = 'Diario';
            return $item;
        });

        $epps = $epps->map(function ($item) {
            $item->type = 'Epps';
            return $item;
        });

        $combined = $cars->concat($toolkits)->concat($dailytoolkits)->concat($epps);

        $sorted = $combined->sortByDesc('created_at');

        return response()->json($sorted->values()->all());
    }

    public function expenseStore(AdditionalCostsApiRequest $request)
    {
        $data = $request->validated();
        $isGEP = false;
        $isStaticOrAdditional = false;
        if ($data['expense_type'] !== PintConstants::COMBUSTIBLE_GEP) {
            $isStaticOrAdditional = true;
        } else {
            $isGEP = true;
        }
        try {
            $doc_date = Carbon::createFromFormat('d/m/Y', $data['doc_date']);
            $startOfMonth = $doc_date->startOfMonth()->format('Y-m-d');
            $endOfMonth = $doc_date->endOfMonth()->format('Y-m-d');

            $projectId = null;

            //MantoPINT
            if ($isStaticOrAdditional) {
                $preprojectId = Preproject::where('date', '>=', $startOfMonth)
                    ->where('cost_center_id', 1)
                    ->where('cost_line_id', 1)
                    ->where('date', '<=', $endOfMonth)
                    ->where('customer_id', 1)
                    ->select('id')
                    ->first();
                $projectId = Project::where('preproject_id', $preprojectId->id)->select('id')->first();
            }
            //GEPPINT
            if ($isGEP) {
                $preprojectId = Preproject::where('date', '>=', $startOfMonth)
                    ->where('cost_center_id', 2)
                    ->where('cost_line_id', 1)
                    ->where('date', '<=', $endOfMonth)
                    ->where('customer_id', 1)
                    ->select('id')
                    ->first();
                $projectId = Project::where('preproject_id', $preprojectId->id)->select('id')->first();
            }
            //Errror if neither exists
            if (!$projectId) {
                return response()->json(['error' => "No se encontraron preproyectos pint para este mes."], 404);
            }

            //Format fields to insert
            $data['project_id'] = $projectId->id;
            $docDate = Carbon::createFromFormat('d/m/Y', $data['doc_date']);
            $data['doc_date'] = $docDate->format('Y-m-d');
            if (($data['zone'] !== PintConstants::MDD1_PM
                    && $data['zone'] !== PintConstants::MDD2_MAZ)
                && $data['type_doc'] === PintConstants::FACTURA
            ) {
                $data['igv'] = 18;
            }
            $newDesc = Auth::user()->name . ", " . $data['description'];
            $data['description'] = $newDesc;
            if (isset($data['photo'])) {
                $data['photo'] = $this->storeBase64Image(
                    $data['photo'],
                    'documents/additionalcosts',
                    'Gasto'
                );
            }
            $data['user_id'] = Auth::user()->id;
            $expense = AdditionalCost::create($data);
            return response()->json([], 200);
            // if ($expense) {
            //     $project = Project::select('description')->find($expense->project_id);
            //     $response = [
            //         'amount' => $expense->amount,
            //         'created_at' => $expense->created_at,
            //         'project' => $project->description,
            //         'zone' => $expense->zone,
            //         'expense_type' => $expense->expense_type,
            //     ];
            //     return response()->json($response, 200);
            // }
            // return response()->json([
            //     'error' => 'Hubo un error al momento de ingresar.Porvafor intentenlo mas tarde.'
            // ], 500);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => $e->getMessage()
            ], 500);
        }
    }



    public function expenseIndex()
    {
        try {
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            $userId = Auth::user()->id;

            $expense = AdditionalCost::where('user_id', $userId)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->select('zone', 'expense_type', 'amount', 'is_accepted', 'description', 'created_at', 'general_expense_id')
                ->get()
                ->toArray();
            $expense2 = StaticCost::where('user_id', $userId)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->select('zone', 'expense_type', 'amount', 'description', 'created_at', 'general_expense_id')
                ->get()
                ->toArray();
            $expense3 = PextProjectExpense::where('user_id', $userId)
                ->whereHas('project', function ($query) {
                    $query->where('cost_line_id', 1);
                })
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->select('zone', 'expense_type', 'amount', 'is_accepted', 'description', 'created_at', 'general_expense_id')
                ->get()
                ->toArray();
            $allExpenses = array_merge($expense, $expense2, $expense3);

            usort($allExpenses, function ($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });

            return response()->json($allExpenses, 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
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
}
