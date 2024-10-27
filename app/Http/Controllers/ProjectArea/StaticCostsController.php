<?php

namespace App\Http\Controllers\ProjectArea;

use App\Exports\StaticCostsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\CostsRequest\StaticCostsRequest;
use App\Models\AccountStatement;
use App\Models\Provider;
use App\Models\StaticCost;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Project;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class StaticCostsController extends Controller
{
    public function index(Request $request, Project $project_id)
    {
        $additional_costs = StaticCost::where('project_id', $project_id->id)->with('project', 'provider')->orderBy('updated_at', 'desc')->paginate(20);
        $additional_costs->getCollection()->transform(function ($item) {
            $item->project->setAppends([]);
            $item->setAppends(['real_amount']);
            return $item;
        });
        $searchQuery = '';
        $providers = Provider::all();
        return Inertia::render('ProjectArea/ProjectManagement/StaticCosts', [
            'additional_costs' => $additional_costs,
            'project_id' => $project_id,
            'providers' => $providers,
            'searchQuery' => $searchQuery
        ]);
    }

    public function search_costs(Request $request, $project_id)
    {
        $result = StaticCost::with('project', 'provider')->where('project_id', $project_id);

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
        if (count($request->selectedZones) < 6) {
            $result = $result->whereIn('zone', $request->selectedZones);
        }
        if (count($request->selectedExpenseTypes) < 9) {
            $result = $result->whereIn('expense_type', $request->selectedExpenseTypes);
        }
        if (count($request->selectedDocTypes) < 5) {
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


    public function store(StaticCostsRequest $request, Project $project_id)
    {
        $data = $request->validated();
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->file_store($request->file('photo'), 'documents/staticcosts/');
        }
        $data['account_statement_id'] = null;
        if(isset($data['operation_number']) && isset($data['operation_date'])){
            $on = substr($data['operation_number'], -6);
            $as = AccountStatement::where('operation_date', $on)
                ->where('operation_number', $data['operation_number'])->first();
            $data['account_statement_id'] = $as?->id;
        }
        $item = StaticCost::create($data);
        $item->load('project', 'provider:id,company_name');
        $item->project->setAppends([]);
        $item->setAppends(['real_amount']);
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
            'type_doc' => 'required|string|in:Efectivo,Deposito,Factura,Boleta,Voucher de Pago',
            'operation_number' => 'nullable|string',
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
            $as = AccountStatement::where('operation_date', $on)
                ->where('operation_number', $data['operation_number'])->first();
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
        $additional_cost->setAppends(['real_amount']);
        return response()->json($additional_cost, 200);
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

    public function downloadImages($project_id)
    {
        try {
            $additionalCosts = StaticCost::where('project_id', $project_id)
                ->whereIn('type_doc', ['Factura', 'Boleta', 'Voucher de Pago'])
                ->get();
            $zipFileName = 'staticCostsPhotos.zip';
            $zipFilePath = public_path("/documents/staticcosts/{$zipFileName}");
            $zip = new ZipArchive;
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                foreach ($additionalCosts as $cost) {
                    if (!empty($cost->photo)) {
                        $photoPath = public_path("/documents/staticcosts/{$cost->photo}");
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


    public function export($project_id)
    {
        return Excel::download(new StaticCostsExport($project_id), 'Gastos_Fijos.xlsx');
    }
}
