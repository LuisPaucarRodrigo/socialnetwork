<?php

namespace App\Http\Controllers\ProjectArea;

use App\Constants\PintConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChecklistRequest\ChecklistCarRequest;
use App\Http\Requests\ChecklistRequest\ChecklistDailytoolkitRequest;
use App\Http\Requests\ChecklistRequest\ChecklistEppRequest;
use App\Http\Requests\ChecklistRequest\ChecklistToolkitRequest;
use App\Http\Requests\CostsRequest\AdditionalCostsApiRequest;
use App\Models\AdditionalCost;
use App\Models\ChecklistCar;
use App\Models\ChecklistDailytoolkit;
use App\Models\ChecklistEpp;
use App\Models\ChecklistToolkit;
use App\Models\Preproject;
use App\Models\Project;
use App\Models\StaticCost;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ChecklistsController extends Controller
{

    public function index()
    {
        return Inertia::render('ProjectArea/Checklist/Index');
    }

    public function car_index()
    {
        $checklistcar = ChecklistCar::with('user')->paginate(20);
        return Inertia::render(
            'ProjectArea/Checklist/ChecklistCar',
            ['checklists' => $checklistcar]
        );
    }

    public function car_photo($id, $photoProp)
    {
        $checklistcar = ChecklistCar::find($id);
        return $this->openNewWindowArchive(
            '/image/checklist/checklistcar/',
            $checklistcar->$photoProp
        );
    }

    public function dailytoolkit_index()
    {
        $checklistdailytoolkit = ChecklistDailytoolkit::with('user')->paginate(20);
        return Inertia::render(
            'ProjectArea/Checklist/ChecklistDailytoolkit',
            ['checklists' => $checklistdailytoolkit]
        );
    }
    public function epp_index()
    {
        $checklistepp = ChecklistEpp::with('user')->paginate(20);
        return Inertia::render(
            'ProjectArea/Checklist/ChecklistEpp',
            ['checklists' => $checklistepp]
        );
    }

    public function toolkit_index()
    {
        $checklisttoolkit = ChecklistToolkit::with('user')->paginate(20);
        return Inertia::render(
            'ProjectArea/Checklist/ChecklistToolkit',
            ['checklists' => $checklisttoolkit]
        );
    }

    public function toolkit_photo($id, $photoProp)
    {
        $checklistcar = ChecklistToolkit::find($id);
        return $this->openNewWindowArchive(
            '/image/checklist/checklisttoolkit/',
            $checklistcar->$photoProp
        );
    }

    public function car_store(ChecklistCarRequest $request)
    {
        $data = $request->validated();
        try {
            $data['front'] = $this->storeBase64Image($data['front'], 'image/checklist/checklistcar', 'front');
            $data['leftSide'] = $this->storeBase64Image($data['leftSide'], 'image/checklist/checklistcar', 'leftSide');
            $data['rightSide'] = $this->storeBase64Image($data['rightSide'], 'image/checklist/checklistcar', 'rightSide');
            $data['interior'] = $this->storeBase64Image($data['interior'], 'image/checklist/checklistcar', 'interior');
            $data['rearLeftTire'] = $this->storeBase64Image($data['rearLeftTire'], 'image/checklist/checklistcar', 'rearLeftTire');
            $data['rearRightTire'] = $this->storeBase64Image($data['rearRightTire'], 'image/checklist/checklistcar', 'rearRightTire');
            $data['frontRightTire'] = $this->storeBase64Image($data['frontRightTire'], 'image/checklist/checklistcar', 'frontRightTire');
            $data['frontLeftTire'] = $this->storeBase64Image($data['frontLeftTire'], 'image/checklist/checklistcar', 'frontLeftTire');
            $data['user_id'] = Auth::user()->id;
            $data['user_name'] = Auth::user()->name;
            ChecklistCar::create($data);
            return response()->json([], 201);
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
            return response()->json([], 201);
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
            return response()->json([], 201);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => 'Ocurrió un error al procesar la solicitud',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function car_destroy($id)
    {
        $checklistCar = ChecklistCar::findOrFail($id);
        $checklistCar->front && $this->file_delete($checklistCar->front, 'image/checklist/checklistcar');
        $checklistCar->leftSide && $this->file_delete($checklistCar->leftSide, 'image/checklist/checklistcar');
        $checklistCar->rightSide && $this->file_delete($checklistCar->rightSide, 'image/checklist/checklistcar');
        $checklistCar->interior && $this->file_delete($checklistCar->interior, 'image/checklist/checklistcar');
        $checklistCar->rearLeftTire && $this->file_delete($checklistCar->rearLeftTire, 'image/checklist/checklistcar');
        $checklistCar->rearRightTire && $this->file_delete($checklistCar->rearRightTire, 'image/checklist/checklistcar');
        $checklistCar->frontRightTire && $this->file_delete($checklistCar->frontRightTire, 'image/checklist/checklistcar');
        $checklistCar->frontLeftTire && $this->file_delete($checklistCar->frontLeftTire, 'image/checklist/checklistcar');
        $checklistCar->delete();
        return redirect()->back();
    }

    public function toolkit_destroy($id)
    {
        $checklistToolkit = ChecklistToolkit::findOrFail($id);
        $checklistToolkit->badTools && $this->file_delete($checklistToolkit->badTools, 'image/checklist/checklisttoolkit');
        $checklistToolkit->goodTools && $this->file_delete($checklistToolkit->goodTools, 'image/checklist/checklisttoolkit');
        $checklistToolkit->delete();
        return redirect()->back();
    }


    public function dailytoolkit_destroy($cdt_id)
    {
        $checklistdailytoolkit = ChecklistDailytoolkit::find($cdt_id);
        $checklistdailytoolkit->delete();
        return redirect()->back();
    }


    public function epp_store(ChecklistEppRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;
        $data['user_name'] = Auth::user()->name;
        ChecklistEpp::create($data);
        return response()->json([], 201);
    }

    public function epp_destroy($epp_id)
    {
        $checklistepp = ChecklistEpp::find($epp_id);
        $checklistepp->delete();
        return redirect()->back();
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

    public function file_delete($filename, $path)
    {
        $file_path = $path . $filename;
        $path = public_path($file_path);
        if (file_exists($path))
            unlink($path);
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

    private function openNewWindowArchive($path, $file)
    {
        $filePath = $path . $file;
        $path = public_path($filePath);
        if (file_exists($path)) {
            ob_end_clean();
            return response()->file($path);
        }
        abort(404, 'Documento no encontrado');
    }

    // public function expenseStore(AdditionalCostsApiRequest $request)
    // {
    //     $data = $request->validated();
    //     $isStatic = $data['expense_type'] === PintConstants::COMBUSTIBLE_UM;
    //     $isGEP = $data['expense_type'] === PintConstants::COMBUSTIBLE_GEP;
    //     $isAdditional = !$isStatic && !$isGEP;
    //     try {
    //         $doc_date = Carbon::createFromFormat('d/m/Y', $data['doc_date']);
    //         $startOfMonth = $doc_date->startOfMonth()->format('Y-m-d');
    //         $endOfMonth = $doc_date->endOfMonth()->format('Y-m-d');

    //         $projectId = null;

    //         //MantoPINT
    //         if ($isStatic || $isAdditional) {
    //             $preprojectId = Preproject::where('date', '>=', $startOfMonth)
    //                 ->where('cost_center_id', 1)
    //                 ->where('cost_line_id', 1)
    //                 ->where('date', '<=', $endOfMonth)
    //                 ->where('customer_id', 1)
    //                 ->select('id')
    //                 ->first();
    //             $projectId = Project::where('preproject_id', $preprojectId->id)->select('id')->first();
    //         }
    //         //GEPPINT
    //         if ($isGEP) {
    //             $preprojectId = Preproject::where('date', '>=', $startOfMonth)
    //                 ->where('cost_center_id', 2)
    //                 ->where('cost_line_id', 1)
    //                 ->where('date', '<=', $endOfMonth)
    //                 ->where('customer_id', 1)
    //                 ->select('id')
    //                 ->first();
    //             $projectId = Project::where('preproject_id', $preprojectId->id)->select('id')->first();
    //         }



    //         //Errror if neither exists
    //         if (!$projectId) {
    //             return response()->json(['error' => "No se encontraron preproyectos pint para este mes."], 404);
    //         }


    //         //Format fields to insert
    //         $data['project_id'] = $projectId->id;
    //         $docDate = Carbon::createFromFormat('d/m/Y', $data['doc_date']);
    //         $data['doc_date'] = $docDate->format('Y-m-d');
    //         if (($data['zone'] !== PintConstants::MDD1_PM
    //                 && $data['zone'] !== PintConstants::MDD2_MAZ)
    //             && $data['type_doc'] === PintConstants::FACTURA
    //         ) {
    //             $data['igv'] = 18;
    //         }
    //         $newDesc = Auth::user()->name . ", " . $data['description'];
    //         $data['description'] = $newDesc;
    //         if (isset($data['photo'])) {
    //             if ($isStatic || $isGEP) {
    //                 $data['photo'] = $this->storeBase64Image($data['photo'], 'documents/staticcosts', 'Gasto');
    //             }
    //             if ($isAdditional) {
    //                 $data['photo'] = $this->storeBase64Image($data['photo'], 'documents/additionalcosts', 'Gasto');
    //             }
    //         }
    //         $data['user_id'] = Auth::user()->id;


    //         //saving data
    //         if ($isStatic || $isGEP) StaticCost::create($data);
    //         if ($isAdditional) AdditionalCost::create($data);

    //         return response()->json(['msg' => "saved"], 200);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'error' => $e->getMessage(),
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }



    public function expenseStore(AdditionalCostsApiRequest $request)
    {
        $data = $request->validated();

        if($data['expense_type'] === PintConstants::COMBUSTIBLE_GEP) {
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
                    $data['photo'], 'documents/additionalcosts', 
                    'Gasto'
                );
                
            }
            $data['user_id'] = Auth::user()->id;
            
            AdditionalCost::create($data);
            return response()->json(['msg' => "saved"], 200);
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
            $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
            $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

            $preprojectId = Preproject::where('date', '>=', $startOfMonth)
                ->where('date', '<=', $endOfMonth)
                ->where('customer_id', 1)
                ->select('id')
                ->first();
            $projectId = Project::where('preproject_id', $preprojectId->id)->select('id')->first();
            if (!$projectId) {
                return response()->json([
                    'error' => $preprojectId
                ], 404);
            }
            $userId = Auth::user()->id;
            $expense = AdditionalCost::where('user_id', $userId)->where('project_id', $projectId->id)->select('zone', 'expense_type', 'amount', 'is_accepted', 'description')->get();
            return response()->json($expense, 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
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
            'zones' => [
                "Arequipa",
                "Moquegua",
                "Tacna",
                "Cuzco",
                "Puno",
                "MDD"
            ],
            'docTypes' => [
                "Sin Comprobante",
                "RH(Recibo por Honorarios)",
                "Factura",
                "Boleta",
                "Ticket",
                "Yape-Plin"
            ],
            'expenseTypes' => [
                "Hospedaje",
                "Pasaje Interprovincial",
                "Peaje",
                "Taxis y Pasajes",
                "Encomienda",
                "Combustible UM",
                "Bandeos",
                "Herramientas",
                "Equipos",
                "Epps",
                "Seguro y Pólizas",
                "Otros"
            ],
        ]);
    }
}
