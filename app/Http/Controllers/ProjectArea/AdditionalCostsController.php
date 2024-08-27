<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\CostsRequest\AdditionalCostsRequest;
use App\Imports\CostsImport;
use App\Models\Provider;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Project;
use App\Models\AdditionalCost;
use App\Exports\AdditionalCostsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class AdditionalCostsController extends Controller
{
    public function index(Project $project_id, $state = null)
    {
        $additional_costs = AdditionalCost::where('project_id', $project_id->id)
            ->where(function($query) use ($state){
                if($state === 'rechazados'){
                    $query->where('is_accepted',0);
                } else {
                    $query->where('is_accepted',1)
                        ->orWhere('is_accepted', null);
                }
            })
            ->with('project', 'provider')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
        $providers = Provider::all();
        return Inertia::render('ProjectArea/ProjectManagement/AdditionalCosts', [
            'additional_costs' => $additional_costs,
            'project_id' => $project_id,
            'providers' => $providers,
            'state' => $state
        ]);
    }
    public function indexRejected(Project $project_id, $state = null)
    {
        $additional_costs = AdditionalCost::where('project_id', $project_id->id)
            ->where(function($query) use ($state){
                if($state === 'rechazados'){
                    $query->where('is_accepted',0);
                } else {
                    $query->where('is_accepted',1)
                        ->orWhere('is_accepted', null);
                }
            })
            ->with('project', 'provider')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
        $providers = Provider::all();
        return Inertia::render('ProjectArea/ProjectManagement/AdditionalCostsRejected', [
            'additional_costs' => $additional_costs,
            'project_id' => $project_id,
            'providers' => $providers,
            'state' => $state
        ]);
    }

    public function search_costs(Request $request, $project_id)
    {
        $result = AdditionalCost::where('project_id', $project_id)->with('project','provider');
        
        if (count($request->selectedZones) < 6) {
            $result = $result->whereIn('zone', $request->selectedZones);
        }
        if (count($request->selectedExpenseTypes) < 11) {
            $result = $result->whereIn('expense_type', $request->selectedExpenseTypes);
        }
        if (count($request->selectedDocTypes) < 5) {
            $result = $result->whereIn('type_doc', $request->selectedDocTypes);
        }
        if ($request->search) {
            $searchTerms = $request->input('search');
            $result = $result->where(function($query) use ($searchTerms){
                $query->where('ruc', 'like', "%$searchTerms%")
                ->orWhere('doc_date', 'like', "%$searchTerms%")
                ->orWhere('description', 'like', "%$searchTerms%")
                ->orWhere('amount', 'like', "%$searchTerms%");
            });
        }
        $result = $result->get();
        return response()->json($result, 200);
    }

    public function store(AdditionalCostsRequest $request, $project_id)
    {
        $data = $request->validated();
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->file_store($request->file('photo'), 'documents/additionalcosts/');
        }
        AdditionalCost::create($data);
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
            'doc_number' => 'nullable|string',
            'doc_date' => 'required|date',
            'amount' => 'required|numeric',
            'zone' => 'required',
            'provider_id' => 'nullable',
            'photo' => 'nullable',
            'igv' => 'required',
            'description' => 'required|string',
        ]);
        
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
        return redirect()->back();
    }

    public function destroy(Project $project_id, AdditionalCost $additional_cost)
    {
        $additional_cost->photo && $this->file_delete($additional_cost->photo, 'documents/additionalcosts/');
        $additional_cost->delete();
        return to_route('projectmanagement.additionalCosts', ['project_id' => $project_id->id]);
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

    public function export($project_id) {
        return Excel::download(new AdditionalCostsExport($project_id), 'Gastos_Variables.xlsx');
    }

    public function import(Request $request, $project_id) {
        $request->validate([
            'import_file' => 'required|mimes:xlsx,csv',
        ]);
        Excel::import(new CostsImport("App\\Models\\AdditionalCost" ,$project_id), $request->file('import_file'));
    }

    public function validateRegister(Request $request, $ac_id){
        $ac = AdditionalCost::with('project', 'provider')
            ->find($ac_id);
        $ac->update([
            'is_accepted'=>$request->is_accepted
        ]);
        return response()->json(['additional_cost'=>$ac], 200);
    }
}
