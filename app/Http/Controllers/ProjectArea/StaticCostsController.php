<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Requests\CostsRequest\StaticCostsRequest;
use App\Services\ProjectArea\StaticCostsService;
use App\Exports\StaticCostsExport;
use App\Http\Controllers\Controller;
use App\Models\PextProjectExpense;
use App\Models\AccountStatement;
use App\Models\StaticCost;
use App\Models\Provider;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use App\Constants\PintConstants;
use ZipArchive;

class StaticCostsController extends Controller
{
    protected StaticCostsService $staticCostsService;

    public function __construct(StaticCostsService $staticCostsService)
    {
        $this->staticCostsService = $staticCostsService;
    }

    public function index(Project $project_id)
    {
        $expenseTypes = PintConstants::scExpenseTypes();
        $docTypes = PintConstants::scDocTypes();
        $zones = PintConstants::scZones();
        $stateTypes = PintConstants::scStatesTypes();

        $additional_costs = StaticCost::where('project_id', $project_id->id)->with('project', 'provider')->orderBy('updated_at', 'desc')->paginate(20);
        $additional_costs->getCollection()->transform(function ($item) {
            $item->project->setAppends([]);
            $item->setAppends(['real_amount', 'real_state']);
            return $item;
        });
        $additionalProjects = Project::where('cost_line_id', 1)->where('cost_center_id', 3)->select('id', 'description')->where('is_accepted', true)->orderBy('description')->get();
        $searchQuery = '';
        $providers = Provider::all();
        return Inertia::render('ProjectArea/ProjectManagement/Pint/StaticCosts', [
            'additional_costs' => $additional_costs,
            'project_id' => $project_id,
            'providers' => $providers,
            'searchQuery' => $searchQuery,
            'zones' => $zones,
            'expenseTypes' => $expenseTypes,
            'docTypes' => $docTypes,
            'stateTypes' => $stateTypes,
            'additional_projects' => $additionalProjects,
        ]);
    }

    public function search_costs(Request $request, $project_id)
    {
        $query = StaticCost::with('project', 'provider')->where('project_id', $project_id);
        $result = $this->staticCostsService->filter($request, $query);
        return response()->json($result, 200);
    }


    public function store(StaticCostsRequest $request, Project $project_id)
    {
        $data = $request->validated();
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->file_store($request->file('photo'), 'documents/staticcosts/');
        }
        $data['account_statement_id'] = null;
        if (isset($data['operation_number']) && isset($data['operation_date'])) {
            $on = substr($data['operation_number'], -6);
            $as = AccountStatement::where('operation_date', $data['operation_date'])
                ->where('operation_number', $on)->first();
            $data['account_statement_id'] = $as?->id;
        }
        $item = StaticCost::create($data);
        $item->load('project', 'provider:id,company_name');
        $item->project->setAppends([]);
        $item->setAppends(['real_amount', 'real_state']);
        return response()->json($item, 200);
    }

    public function download_ac_photo(StaticCost $static_cost_id)
    {
        $fileName = $static_cost_id->photo;
        $filePath = '/documents/staticcosts/' . $fileName;
        $path = public_path($filePath);
        if (file_exists($path)) {
            ob_end_clean();
            return response()->file($path);
        }
        abort(404, 'Documento no encontrado');
    }

    public function update(Request $request, StaticCost $additional_cost)
    {
        $data = $request->validate([
            'expense_type' => 'required|string',
            'ruc' => 'required|numeric|digits:11',
            'type_doc' => 'required',
            'operation_number' => 'nullable|string',
            'operation_date' => 'nullable|date',
            'doc_number' => 'nullable|string',
            'doc_date' => 'nullable|date',
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
        }
        if ($request->hasFile('photo')) {
            $filename = $additional_cost->photo;
            if ($filename) {
                $this->file_delete($filename, 'documents/staticcosts/');
            }
            $data['photo'] = $this->file_store($request->file('photo'), 'documents/staticcosts/');

        } else if ($request->photo_status === 'stable') {
            $filename = $additional_cost->photo;
            if ($filename) {
                unset($data["photo"]);
            }
        }

        if ($request->photo_status === 'delete') {
            $filename = $additional_cost->photo;
            if ($filename) {
                $this->file_delete($filename, 'documents/staticcosts/');
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
        $costs = StaticCost::whereIn('id', $data['ids'])->get();
        foreach ($costs as $cost) {
            $cost->update([
                'operation_date' => $data['operation_date'],
                'operation_number' => $data['operation_number'],
                'account_statement_id' => $data['account_statement_id'],
            ]);
        }
        $updatedCosts = StaticCost::whereIn('id', $data['ids'])
            ->with(['project', 'provider:id,company_name'])
            ->get();
        $updatedCosts->each(function ($cost) {
            $cost->project->setAppends([]);
            $cost->setAppends(['real_amount', 'real_state']);
        });
        return response()->json($updatedCosts, 200);
    }



    public function destroy(Project $project_id, StaticCost $additional_cost)
    {
        $additional_cost->photo && $this->file_delete($additional_cost->photo, 'documents/staticcosts/');
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

    public function downloadImages(Request $request, $project_id)
    {
        try {
            $query = StaticCost::where('project_id', $project_id);
            $staticCosts = $this->staticCostsService->filter($request, $query);
            $zipFileName = 'staticCostsPhotos.zip';
            $zipFilePath = public_path("/documents/staticcosts/{$zipFileName}");
            $zip = new ZipArchive;
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                foreach ($staticCosts as $cost) {
                    if (!empty($cost["photo"])) {
                        $photoPath = public_path("/documents/staticcosts/{$cost["photo"]}");
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


    public function export($project_id)
    {
        return Excel::download(new StaticCostsExport($project_id), 'Gastos_Fijos.xlsx');
    }


    public function swapCostsToAdditionalProject(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required | array | min:1',
            'project_id' => 'required'
        ]);
        foreach ($data['ids'] as $id) {
            $sc = StaticCost::find($id);
            $newData = collect($sc->toArray())->except(['id', 'project_id'])->toArray();
            $newData['project_id'] = $data['project_id'];
            $sc->photo && $this->file_move_toAdditional($sc->photo);
            $newData['fixedOrAdditional'] = true;
            $newData['is_accepted'] = true;
            PextProjectExpense::create($newData);
        }
        StaticCost::whereIn('id', $data['ids'])->delete();
        return response()->json(true, 200);
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
}
