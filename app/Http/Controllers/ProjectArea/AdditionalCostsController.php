<?php

namespace App\Http\Controllers\ProjectArea;

use App\Constants\PintConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\CostsRequest\AdditionalCostsRequest;
use App\Imports\CostsImport;
use App\Models\AccountStatement;
use App\Models\Provider;
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
    public function index(Project $project_id)
    {
        $expenseTypes = PintConstants::acExpenseTypes();
        $docTypes = PintConstants::acDocTypes();
        $zones = PintConstants::acZones();
        $stateTypes = PintConstants::acStatesPenAccep();

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
            $item->setAppends(['real_amount']);
            return $item;
        });


        $providers = Provider::all();
        return Inertia::render('ProjectArea/ProjectManagement/AdditionalCosts', [
            'additional_costs' => $additional_costs,
            'project_id' => $project_id,
            'providers' => $providers,
            'zones' => $zones,
            'expenseTypes' => $expenseTypes,
            'docTypes' => $docTypes,
            'stateTypes' => $stateTypes,
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
            $item->setAppends(['real_amount']);
            return $item;
        });
        $providers = Provider::all();
        return Inertia::render('ProjectArea/ProjectManagement/AdditionalCostsRejected', [
            'additional_costs' => $additional_costs,
            'project_id' => $project_id,
            'providers' => $providers,
        ]);
    }

    public function search_costs(Request $request, $project_id)
    {
        

        $result = AdditionalCost::where('project_id', $project_id)->with([
            'project:id,description',
            'provider'
        ]);

        if ($request->search) {
            $searchTerms = $request->input('search');
            $result = $result->where(function ($query) use ($searchTerms) {
                $query->where('ruc', 'like', "%$searchTerms%")
                    ->orWhere('doc_number', 'like', "%$searchTerms%")
                    ->orWhere('operation_number', 'like', "%$searchTerms%")
                    ->orWhere('description', 'like', "%$searchTerms%")
                    ->orWhere('amount', 'like', "%$searchTerms%");
            });
        }

        if ($request->state === false) {
            $result->where('is_accepted', 0);
        } else {
            if (count($request->selectedStateTypes) < count(PintConstants::acStatesPenAccep())) {
                $newSS = array_values(array_map(function ($item) {
                    if ($item === 'Aceptado') return '1';
                    if ($item === 'Pendiente') return null;
                }, $request->selectedStateTypes));
                $acceptedValues = array_filter($newSS, fn($value) => $value !== null);
                $result = $result->when(
                    in_array(null, $newSS, true),
                    fn($query) => $query->whereNull('is_accepted'),
                    fn($query) => $query->whereIn('is_accepted', $acceptedValues)
                );
            } else {
                $result->where(function ($query) {
                    $query->where('is_accepted', 1)
                        ->orWhere('is_accepted', null);
                });
            }
        }

        if ($request->docNoDate) {
            $result->where('doc_date', null);
        }
        if ($request->docStartDate) {
            $result->where('doc_date', '>=', $request->docStartDate);
        }
        if ($request->docEndDate) {
            $result->where('doc_date', '<=', $request->docEndDate);
        }
        if ($request->opNoDate) {
            $result->where('operation_date', null);
        }
        if ($request->opStartDate) {
            $result->where('operation_date', '>=', $request->opStartDate);
        }
        if ($request->opEndDate) {
            $result->where('operation_date', '<=', $request->opEndDate);
        }
        if (count($request->selectedZones) < PintConstants::countAcZones()) {
            $result = $result->whereIn('zone', $request->selectedZones);
        }
        if (count($request->selectedExpenseTypes) < PintConstants::countAcExpenseTypes()) {
            $result = $result->whereIn('expense_type', $request->selectedExpenseTypes);
        }
        if (count($request->selectedDocTypes) < PintConstants::countAcDocTypes()) {
            $result = $result->whereIn('type_doc', $request->selectedDocTypes);
        }
        $result = $result->orderBy('doc_date')->get();

        $result->transform(function ($item) {
            $item->project->setAppends([]);
            $item->setAppends(['real_amount']);
            return $item;
        });

        return response()->json($result, 200);
    }

    public function store(AdditionalCostsRequest $request, $project_id)
    {
        $data = $request->validated();
        $data['is_accepted'] = true;
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->file_store($request->file('photo'), 'documents/additionalcosts/');
        }
        $data['account_statement_id'] = null;
        if(isset($data['operation_number']) && isset($data['operation_date'])){
            $on = substr($data['operation_number'], -6);
            $as = AccountStatement::where('operation_date', $data['operation_date'])
                ->where('operation_number', $on)->first();
            $data['account_statement_id'] = $as?->id;
        }
        $item = AdditionalCost::create($data);
        $item->load('project', 'provider:id,company_name');
        $item->project->setAppends([]);
        $item->setAppends(['real_amount']);
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
            'ruc' => 'required|numeric|digits:11',
            'type_doc' => 'required|string|in:Efectivo,Deposito,Factura,Boleta,Voucher de Pago',
            'operation_number' => 'nullable | min:6',
            'operation_date' => 'nullable|date',
            'doc_number' => 'nullable|string',
            'doc_date' => 'required|date',
            'amount' => 'required|numeric',
            'zone' => 'required',
            'provider_id' => 'nullable',
            'photo' => 'nullable',
            'igv' => 'required',
            'description' => 'required|string',
        ]);
        $data['account_statement_id'] = null;
        if(isset($data['operation_number']) && isset($data['operation_date'])){
            $on = substr($data['operation_number'], -6);
            $as = AccountStatement::where('operation_date', $data['operation_date'])
                ->where('operation_number', $on)->first();
            $data['account_statement_id'] = $as?->id;
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
        $additional_cost->setAppends(['real_amount']);
        return response()->json($additional_cost, 200);
    }

    public function masiveUpdate (Request $request) {
        $data = $request->validate([
            'ids' => 'required | array | min:1',
            'ids.*' => 'integer',
            'operation_date' => 'required|date',
            'operation_number' => 'required|min:6',
        ]);
        $on = substr($data['operation_number'], -6);
        $as = AccountStatement::where('operation_date', $data['operation_date'])
                ->where('operation_number', $on)->first();
        $data['account_statement_id'] = $as?->id;

        AdditionalCost::whereIn('id', $data['ids'])->update([
            'operation_date' => $data['operation_date'],
            'operation_number' => $data['operation_number'],
            'account_statement_id' => $data['account_statement_id'],
        ]);
        $updatedCosts = AdditionalCost::whereIn('id', $data['ids'])
        ->with(['project', 'provider:id,company_name'])
        ->get();
        $updatedCosts->each(function ($cost) {
        $cost->project->setAppends([]);
        $cost->setAppends(['real_amount']);
        });
        return response()->json($updatedCosts, 200);
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
        $ac = AdditionalCost::with('project', 'provider')
            ->find($ac_id);
        $ac->update([
            'is_accepted' => $request->is_accepted
        ]);
        return response()->json(['additional_cost' => $ac], 200);
    }


    public function downloadImages($project_id)
    {
        try {
            $additionalCosts = AdditionalCost::where('project_id', $project_id)
                ->where('is_accepted', 1)
                ->whereIn('type_doc', ['Factura', 'Boleta', 'Voucher de Pago'])
                ->get();
            $zipFileName = 'additionalCostsPhotos.zip';
            $zipFilePath = public_path("/documents/additionalcosts/{$zipFileName}");
            $zip = new ZipArchive;
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                foreach ($additionalCosts as $cost) {
                    if (!empty($cost->photo)) {
                        $photoPath = public_path("/documents/additionalcosts/{$cost->photo}");
                        if (file_exists($photoPath)) {
                            $zip->addFile($photoPath, $cost->photo);
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
