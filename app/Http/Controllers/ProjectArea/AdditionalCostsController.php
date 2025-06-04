<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Requests\CostsRequest\AdditionalCostsRequest;
use App\Services\ProjectArea\AdditionalCostsService;
use App\Constants\PintConstants;
use App\Http\Controllers\Controller;
use App\Imports\CostsImport;
use App\Models\AccountStatement;
use App\Models\PextProjectExpense;
use App\Models\Provider;
use App\Models\StaticCost;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Project;
use App\Models\AdditionalCost;
use App\Exports\AdditionalCostsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use ZipArchive;

class AdditionalCostsController extends Controller
{
    protected AdditionalCostsService $additionalCostsService;

    public function __construct(AdditionalCostsService $additionalCostsService)
    {
        $this->additionalCostsService = $additionalCostsService;
    }


    public function index(Project $project_id)
    {
        $expenseTypes = PintConstants::acExpenseTypes();
        $docTypes = PintConstants::acDocTypes();
        $zones = PintConstants::acZones();
        $stateTypes = PintConstants::acStatesPenAccep();
        $additionalProjects = Project::where('cost_line_id', 1)->where('cost_center_id', 3)->select('id', 'description')->where('is_accepted', true)->orderBy('description')->get();

        $additional_costs = AdditionalCost::where('project_id', $project_id->id)
            ->where(function ($query) {
                $query->where('is_accepted', 1)
                    ->orWhere('is_accepted', null);
            })
            ->with(['project', 'provider'])
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        $additional_costs->getCollection()->transform(function ($item) {
            $item->project->setAppends([]);
            $item->setAppends(['real_amount', 'real_state']);
            return $item;
        });


        $providers = Provider::all();
        return Inertia::render('ProjectArea/ProjectManagement/Pint/AdditionalCosts', [
            'additional_costs' => $additional_costs,
            'project_id' => $project_id,
            'providers' => $providers,
            'zones' => $zones,
            'expenseTypes' => $expenseTypes,
            'docTypes' => $docTypes,
            'stateTypes' => $stateTypes,
            'additional_projects' => $additionalProjects,
        ]);
    }
    public function indexRejected(Project $project_id)
    {
        $additional_costs = AdditionalCost::where('project_id', $project_id->id)
            ->where('is_accepted', 0)
            ->with('project', 'provider')
            ->orderBy('updated_at', 'desc')
            ->get();
        $additional_costs->transform(function ($item) {
            $item->project->setAppends([]);
            $item->setAppends(['real_amount', 'real_state']);
            return $item;
        });
        $providers = Provider::all();
        return Inertia::render('ProjectArea/ProjectManagement/Pint/AdditionalCostsRejected', [
            'additional_costs' => $additional_costs,
            'project_id' => $project_id,
            'providers' => $providers,
        ]);
    }

    public function search_costs(Request $request, $project_id)
    {
        $query = AdditionalCost::where('project_id', $project_id)->with([
            'project:id,description',
            'provider'
        ]);
        $data = $this->additionalCostsService->filter($request, $query);
        return response()->json($data, 200);
    }

    public function store(AdditionalCostsRequest $request, $project_id)
    {
        $data = $request->validated();
        $data['is_accepted'] = true;
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->file_store($request->file('photo'), 'documents/additionalcosts/');
        }
        $data['account_statement_id'] = null;
        if (isset($data['operation_number']) && isset($data['operation_date'])) {
            $on = substr($data['operation_number'], -6);
            $as = AccountStatement::where('operation_date', $data['operation_date'])
                ->where('operation_number', $on)->first();
            $data['account_statement_id'] = $as?->id;
        }
        $item = AdditionalCost::create($data);
        $item->load('project', 'provider:id,company_name');
        $item->project->setAppends([]);
        $item->setAppends(['real_amount', 'real_state']);
        return response()->json($item, 200);
    }

    public function download_ac_photo(AdditionalCost $additional_cost_id)
    {
        $fileName = $additional_cost_id->photo;
        $filePath = '/documents/additionalcosts/' . $fileName;
        $path = public_path($filePath);
        if (file_exists($path)) {
            ob_end_clean();
            return response()->file($path);
        }
        abort(404, 'Documento no encontrado');
    }


    public function update(Request $request, AdditionalCost $additional_cost)
    {
        $data = $request->validate([
            'expense_type' => 'required|string',
            'ruc' => $request->input('type_doc') === PintConstants::SIN_COMPROBANTE ? 'nullable' : 'required|numeric|digits:11',
            'type_doc' => 'required',
            'operation_number' => 'nullable | min:6',
            'operation_date' => 'nullable|date',
            'doc_number' => $request->input('type_doc') === PintConstants::SIN_COMPROBANTE ? 'nullable' : 'nullable|string',
            'doc_date' => 'required|date',
            'amount' => 'required|numeric',
            'zone' => 'required',
            'provider_id' => 'nullable',
            'photo' => 'nullable',
            'igv' => 'required',
            'description' => 'required|string',
        ]);
        $data['account_statement_id'] = null;
        if (isset($data['operation_number']) && isset($data['operation_date'])) {
            $on = substr($data['operation_number'], -6);
            $as = AccountStatement::where('operation_date', $data['operation_date'])
                ->where('operation_number', $on)->first();
            $data['account_statement_id'] = $as?->id;
            $data['is_accepted'] = 1;
        } else {
            $data['is_accepted'] = null;
        }

        if ($request->hasFile('photo')) {
            $filename = $additional_cost->photo;
            if ($filename) {
                $this->file_delete($filename, 'documents/additionalcosts/');
            }
            $data['photo'] = $this->file_store($request->file('photo'), 'documents/additionalcosts/');
        } else if ($request->photo_status === 'stable') {
            $filename = $additional_cost->photo;
            if ($filename) {
                unset($data["photo"]);
            }
        }

        if ($request->photo_status === 'delete') {
            $filename = $additional_cost->photo;
            if ($filename) {
                $this->file_delete($filename, 'documents/additionalcosts/');
            }
        }
        $additional_cost->update($data);
        $additional_cost->load('project', 'provider:id,company_name');
        $additional_cost->project->setAppends([]);
        $additional_cost->setAppends(['real_amount', 'real_state']);
        return response()->json($additional_cost, 200);
    }

    public function masiveUpdate(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
            'operation_date' => 'required|date',
            'operation_number' => 'required|min:6',
        ]);
        $on = substr($data['operation_number'], -6);
        $as = AccountStatement::where('operation_date', $data['operation_date'])
            ->where('operation_number', $on)
            ->first();

        $data['account_statement_id'] = $as?->id;
        $costs = AdditionalCost::whereIn('id', $data['ids'])->get();
        foreach ($costs as $cost) {
            $cost->update([
                'operation_date' => $data['operation_date'],
                'operation_number' => $data['operation_number'],
                'account_statement_id' => $data['account_statement_id'],
                'is_accepted' => 1,
            ]);

            //Automatic swap
            // if ($cost->is_accepted) {
            //     $project = Project::with('preproject.quote')->find($cost->project_id);
            //     if ($cost->expense_type === PintConstants::COMBUSTIBLE_UM) {
            //         $newdata = collect($cost->toArray())->except(['id', 'is_accepted'])->toArray();
            //         StaticCost::create($newdata);
            //         $cost->photo && $this->file_move($cost->photo);
            //         $cost->delete();
            //     }
            //     if ($cost->expense_type === PintConstants::COMBUSTIBLE_GEP) {
            //         if ($project->cost_line_id === 1 && $project->cost_center_id === 2) {
            //             // from gep project
            //             $newdata = collect($cost->toArray())->except(['id', 'is_accepted'])->toArray();
            //             StaticCost::create($newdata);
            //             $cost->photo && $this->file_move($cost->photo);
            //             $cost->delete();
            //         } else {
            //             // from mantto project
            //             $projectGEP = Project::where('cost_line_id', 1)->where('cost_center_id', 2)
            //                 ->whereHas('preproject', function ($query) use ($project) {
            //                     $query->whereHas('quote', function ($subquery) use ($project) {
            //                         $subquery->where('date', $project->preproject?->quote?->date)
            //                             ->where('deliverable_time', $project->preproject?->quote?->deliverable_time);
            //                     });
            //                 })
            //                 ->first();
            //             if ($projectGEP) {
            //                 $newdata = collect($cost->toArray())->except(['id', 'is_accepted'])->toArray();
            //                 $newdata['project_id'] = $projectGEP->id;
            //                 StaticCost::create($newdata);
            //                 $cost->photo && $this->file_move($cost->photo);
            //                 $cost->delete();
            //             }
            //         }
            //     }
            // }
        }
        $updatedCosts = AdditionalCost::whereIn('id', $data['ids'])
            ->with(['project', 'provider:id,company_name'])
            ->get();
        $updatedCosts->each(function ($cost) {
            $cost->project->setAppends([]);
            $cost->setAppends(['real_amount', 'real_state']);
        });
        return response()->json($updatedCosts, 200);
    }




    public function swapCosts(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required | array | min:1',
        ]);
        foreach ($data['ids'] as $id) {
            $ac = AdditionalCost::find($id);
            $ac->photo && $this->file_move($ac->photo);
            StaticCost::create([
                'expense_type' => $ac->expense_type,
                'ruc' => $ac->ruc,
                'type_doc' => $ac->type_doc,
                'zone' => $ac->zone,
                'operation_number' => $ac->operation_number,
                'operation_date' => $ac->operation_date,
                'doc_number' => $ac->doc_number,
                'doc_date' => $ac->doc_date,
                'description' => $ac->description,
                'amount' => $ac->amount,
                'project_id' => $ac->project_id,
                'provider_id' => $ac->provider_id,
                'igv' => $ac->igv,
                'photo' => $ac->photo,
                'account_statement_id' => $ac->account_statement_id,
            ]);
        }
        AdditionalCost::whereIn('id', $data['ids'])->delete();
        return response()->json(true, 200);
    }

    public function swapCostsToAdditionalProject(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required | array | min:1',
            'project_id' => 'required'
        ]);
        foreach ($data['ids'] as $id) {
            $ac = AdditionalCost::find($id);
            $newData = collect($ac->toArray())->except(['id', 'project_id'])->toArray();
            $newData['project_id'] = $data['project_id'];
            $ac->photo && $this->file_move_toAdditional($ac->photo);
            // $newData['fixedOrAdditional'] = ($ac->expense_type === PintConstants::COMBUSTIBLE_GEP || $ac->expense_type === PintConstants::COMBUSTIBLE_UM) ? true : false;
            $newData['fixedOrAdditional'] = false;
            PextProjectExpense::create($newData);
        }
        AdditionalCost::whereIn('id', $data['ids'])->delete();
        return response()->json(true, 200);
    }

    public function getRegularProjects()
    {
        $data = Project::where('cost_line_id', 1)
            ->where(function ($query) {
                $query->where('cost_center_id', 1)
                    ->orWhere('cost_center_id', 2);
            })
            ->get();
        return response()->json($data);
    }

    public function swapCostsToRegularProject(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required | array | min:1',
            'project_id' => 'required'
        ]);
        foreach ($data['ids'] as $id) {
            $ac = AdditionalCost::find($id);
            $ac->update(['project_id' => $data['project_id']]);
        }
        return response()->json(true, 200);
    }

    public function destroy(Project $project_id, AdditionalCost $additional_cost)
    {
        $additional_cost->photo && $this->file_delete($additional_cost->photo, 'documents/additionalcosts/');
        $additional_cost->delete();
        return response()->json(['msg' => 'success'], 200);
    }


    public function file_store($file, $path)
    {
        $name = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path($path), $name);
        return $name;
    }

    public function file_delete($filename, $path)
    {
        $file_path = $path . $filename;
        $path = public_path($file_path);
        if (file_exists($path))
            unlink($path);
    }

    public function file_move($fileName)
    {
        $sourcePath = public_path('documents/additionalcosts/' . $fileName);
        $destinationPath = public_path('documents/staticcosts/' . $fileName);
        if (file_exists($sourcePath)) {
            rename($sourcePath, $destinationPath);
            return true;
        }
        return true;
    }
    public function file_move_toAdditional($fileName)
    {
        $sourcePath = public_path('documents/additionalcosts/' . $fileName);
        $destinationPath = public_path('documents/expensesPext/' . $fileName);
        if (file_exists($sourcePath)) {
            rename($sourcePath, $destinationPath);
            return true;
        }
        return true;
    }

    public function export($project_id)
    {
        return Excel::download(new AdditionalCostsExport($project_id), 'Gastos_Variables.xlsx');
    }

    public function import(Request $request, $project_id)
    {
        $request->validate([
            'import_file' => 'required|mimes:xlsx,csv',
        ]);
        Excel::import(new CostsImport("App\\Models\\AdditionalCost", $project_id), $request->file('import_file'));
    }

    public function validateRegister(Request $request, $ac_id)
    {
        if ($request->is_accepted === 1) {
            $data = $request->validate([
                'operation_date' => 'required|date',
                'operation_number' => 'required|min:6',
                'is_accepted' => 'required'
            ]);
        } else {
            $data = ['is_accepted' => $request->is_accepted];
        }

        $ac = AdditionalCost::with('project', 'provider')
            ->find($ac_id);
        $ac->update($data);

        //Automatic swap
        // if ($ac->is_accepted) {
        //     $project = Project::with('preproject.quote')->find($ac->project_id);
        //     if ($ac->expense_type === PintConstants::COMBUSTIBLE_UM) {
        //         $newdata = collect($ac->toArray())->except(['id', 'is_accepted'])->toArray();
        //         StaticCost::create($newdata);
        //         $ac->photo && $this->file_move($ac->photo);
        //         $ac->delete();
        //         return response()->json(['additional_cost' => null, 'msg' => 'Validación de gasto exitosa (fue movido a los gastos fijos)'], 200);
        //     }
        //     if ($ac->expense_type === PintConstants::COMBUSTIBLE_GEP) {
        //         if ($project->cost_line_id === 1 && $project->cost_center_id === 2) {
        //             $newdata = collect($ac->toArray())->except(['id', 'is_accepted'])->toArray();
        //             StaticCost::create($newdata);
        //             $ac->photo && $this->file_move($ac->photo);
        //             $ac->delete();
        //             return response()->json(['additional_cost' => null, 'msg' => 'Validación de gasto exitosa (fue movido a los gastos fijos de GEP)'], 200);
        //         } else {
        //             //find mantto project}
        //             $projectGEP = Project::where('cost_line_id', 1)->where('cost_center_id', 2)
        //                 ->whereHas('preproject', function ($query) use ($project) {
        //                     $query->whereHas('quote', function ($subquery) use ($project) {
        //                         $subquery->where('date', $project->preproject?->quote?->date)
        //                             ->where('deliverable_time', $project->preproject?->quote?->deliverable_time);
        //                     });
        //                 })
        //                 ->first();
        //             if ($projectGEP) {
        //                 $newdata = collect($ac->toArray())->except(['id', 'is_accepted'])->toArray();
        //                 $newdata['project_id'] = $projectGEP->id;
        //                 StaticCost::create($newdata);
        //                 $ac->photo && $this->file_move($ac->photo);
        //                 $ac->delete();
        //                 return response()->json(['additional_cost' => null, 'msg' => 'Validación de gasto exitosa (fue movido a los gastos fijos de GEP)'], 200);
        //             }
        //         }
        //     }
        // }
        return response()->json(['additional_cost' => $ac, 'msg' => 'Validación de gasto exitosa'], 200);
    }


    public function downloadImages(Request $request, $project_id)
    {
        try {
            $query = AdditionalCost::where('project_id', $project_id)
                ->where('is_accepted', 1);
            $additionalCosts = $this->additionalCostsService->filter($request, $query);
            $zipFileName = 'additionalCostsPhotos.zip';
            $zipFilePath = public_path("/documents/additionalcosts/{$zipFileName}");
            $zip = new ZipArchive;
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                foreach ($additionalCosts as $cost) {
                    if (!empty($cost["photo"])) {
                        $photoPath = public_path("/documents/additionalcosts/{$cost["photo"]}");
                        if (file_exists($photoPath)) {
                            $zip->addFile($photoPath, $cost["photo"]);
                        }
                    }
                }
                $zip->close();
                ob_end_clean();
                return response()->download($zipFilePath)->deleteFileAfterSend(true);
            } else {
                Log::error('No se pudo abrir el archivo ZIP para escritura.');
                return response()->json(['error' => 'No se pudo abrir el archivo ZIP para escritura.'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error al crear el archivo ZIP: ' . $e->getMessage());
            return response()->json(['error' => 'No se pudo crear el archivo ZIP.'], 500);
        }
    }
}
