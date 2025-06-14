<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Constants\PintConstants;
use App\Models\MonthProject;
use App\Models\AdministrativeCost;
use App\Models\Provider;
use App\Models\AccountStatement;
use Inertia\Inertia;
use App\Http\Requests\CostsRequest\AdministrativeCostsRequest;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use App\Exports\AdministrativeCostsExport;
use ZipArchive;

class AdministrativeCostsController extends Controller
{
    public function index(MonthProject $month_project_id)
    {
        $expenseTypes = PintConstants::admincostExpenseTypes();
        $docTypes = PintConstants::admincostDocTypes();
        $zones = PintConstants::scZones();
        $stateTypes = PintConstants::scStatesTypes();

        $additional_costs = AdministrativeCost::where('month_project_id', $month_project_id->id)->with('month_project', 'provider')->orderBy('updated_at', 'desc')->paginate(20);
        $additional_costs->getCollection()->transform(function ($item) {
            $item->month_project->setAppends([]);
            $item->setAppends(['real_amount', 'real_state']);
            return $item;
        });
        $searchQuery = '';
        $providers = Provider::all();
        return Inertia::render('ProjectArea/ProjectManagement/Administrative/AdministrativeCosts', [
            'additional_costs' => $additional_costs,
            'month_project_id' => $month_project_id,
            'providers' => $providers,
            'searchQuery' => $searchQuery,
            'zones' => $zones,
            'expenseTypes' => $expenseTypes,
            'docTypes' => $docTypes,
            'stateTypes' => $stateTypes,
        ]);
    }

    public function search_costs(Request $request, $month_project_id)
    {
        $result = AdministrativeCost::with('month_project', 'provider')->where('month_project_id', $month_project_id);

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
        if (count($request->selectedZones) < PintConstants::countScZones()) {
            $result = $result->whereIn('zone', $request->selectedZones);
        }
        if (count($request->selectedExpenseTypes) < PintConstants::countAdmincostExpenseTypes()) {
            $result = $result->whereIn('expense_type', $request->selectedExpenseTypes);
        }
        if (count($request->selectedDocTypes) < PintConstants::countAdmincostDocTypes()) {
            $result = $result->whereIn('type_doc', $request->selectedDocTypes);
        }
        $result = $result->orderBy('doc_date')->get();
        $result->transform(function ($item) {
            $item->month_project->setAppends([]);
            $item->setAppends(['real_amount', 'real_state']);
            return $item;
        });
        if (count($request->selectedStateTypes) < PintConstants::countScStatesTypes()) {
            $result = $result->filter(function ($item) use ($request) {
                return in_array($item->real_state, $request->selectedStateTypes);
            })->values()->all();
        }
        return response()->json($result, 200);
    }


    public function store(AdministrativeCostsRequest $request, MonthProject $month_project_id)
    {
        $data = $request->validated();
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->file_store($request->file('photo'), 'documents/administrativecosts/');
        }
        $data['account_statement_id'] = null;
        if (isset($data['operation_number']) && isset($data['operation_date'])) {
            $on = substr($data['operation_number'], -6);
            $as = AccountStatement::where('operation_date', $data['operation_date'])
                ->where('operation_number', $on)->first();
            $data['account_statement_id'] = $as?->id;
        }
        $item = AdministrativeCost::create($data);
        $item->load('month_project', 'provider:id,company_name');
        $item->month_project->setAppends([]);
        $item->setAppends(['real_amount', 'real_state']);
        return response()->json($item, 200);
    }

    public function download_ac_photo(AdministrativeCost $static_cost_id)
    {
        $fileName = $static_cost_id->photo;
        $filePath = '/documents/administrativecosts/' . $fileName;
        $path = public_path($filePath);
        if (file_exists($path)) {
            ob_end_clean();
            return response()->file($path);
        }
        abort(404, 'Documento no encontrado');
    }

    public function update(Request $request, AdministrativeCost $additional_cost)
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
                $this->file_delete($filename, 'documents/administrativecosts/');
            }
            $data['photo'] = $this->file_store($request->file('photo'), 'documents/administrativecosts/');
        } else if ($request->photo_status === 'stable') {
            $filename = $additional_cost->photo;
            if ($filename) {
                unset($data["photo"]);
            }
        }

        if ($request->photo_status === 'delete') {
            $filename = $additional_cost->photo;
            if ($filename) {
                $this->file_delete($filename, 'documents/administrativecosts/');
            }
        }
        $additional_cost->update($data);
        $additional_cost->load('month_project', 'provider:id,company_name');
        $additional_cost->month_project->setAppends([]);
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
        $costs = AdministrativeCost::whereIn('id', $data['ids'])->get();
        foreach ($costs as $cost) {
            $cost->update([
                'operation_date' => $data['operation_date'],
                'operation_number' => $data['operation_number'],
                'account_statement_id' => $data['account_statement_id'],
            ]);
        }
        $updatedCosts = AdministrativeCost::whereIn('id', $data['ids'])
            ->with(['month_project', 'provider:id,company_name'])
            ->get();
        $updatedCosts->each(function ($cost) {
            $cost->month_project->setAppends([]);
            $cost->setAppends(['real_amount', 'real_state']);
        });
        return response()->json($updatedCosts, 200);
    }



    public function destroy(MonthProject $month_project_id, AdministrativeCost $additional_cost)
    {
        $additional_cost->photo && $this->file_delete($additional_cost->photo, 'documents/administrativecosts/');
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

    public function downloadImages($month_project_id)
    {
        try {
            $additionalCosts = AdministrativeCost::where('month_project_id', $month_project_id)
                // ->whereIn('type_doc', ['Factura', 'Boleta', 'Voucher de Pago'])
                ->get();
            $zipFileName = 'administrativeCostsPhoto.zip';
            $zipFilePath = public_path("/documents/administrativecosts/{$zipFileName}");
            $zip = new ZipArchive;
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                foreach ($additionalCosts as $cost) {
                    if (!empty($cost->photo)) {
                        $photoPath = public_path("/documents/administrativecosts/{$cost->photo}");
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
            return response()->json(['error' => 'No hay imágenes.'], 500);
        }
    }


    public function export($month_project_id)
    {
        return Excel::download(new AdministrativeCostsExport($month_project_id), 'Gastos_Administrativos.xlsx');
    }
}
