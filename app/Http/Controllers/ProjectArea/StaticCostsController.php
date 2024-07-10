<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\StaticCost;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Project;
use Illuminate\Support\Facades\Log;
class StaticCostsController extends Controller
{
    public function index(Request $request, Project $project_id)
    {
        $additional_costs = StaticCost::where('project_id', $project_id->id)->with('project', 'provider')->orderBy('updated_at')->paginate(20);
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
        $result = StaticCost::where('project_id', $project_id);
        if (count($request->selectedZones) < 5) {
            $result = $result->whereIn('zone', $request->selectedZones);
        }
        if (count($request->selectedExpenseTypes) < 6) {
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

    public function store(Project $project_id, Request $request)
    {
        $remaining_budget = $project_id->remaining_budget;

        $data = $request->validate([
            'expense_type' => 'required|string',
            'ruc' => 'required|numeric|digits:11',
            'type_doc' => 'required|string|in:Efectivo,Deposito,Factura,Boleta,Voucher de Pago',
            'doc_number' => 'nullable|string',
            'doc_date' => 'required|date',
            'amount' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) use ($request, $remaining_budget) {
                    if ($value > $remaining_budget) {
                        $fail(__('El monto del gasto excede el presupuesto restante. S/. ' . number_format($remaining_budget, 2)));
                    }
                }
            ],
            'zone' => 'required',
            'provider_id' => 'nullable',
            'description' => 'required|string',
            'photo' => 'nullable',
            'project_id' => 'required'
        ]);
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->file_store($request->file('photo'), 'documents/staticcosts/');
        }

        StaticCost::create($data);
        return redirect()->back();
    }

    public function download_ac_photo(StaticCost $additional_cost_id)
    {
        $fileName = $additional_cost_id->photo;
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
            'doc_number' => 'nullable|string',
            'doc_date' => 'required|date',
            'amount' => 'required|numeric',
            'zone' => 'required',
            'provider_id' => 'nullable',
            'photo' => 'nullable',
            'description' => 'required|string',
        ]);
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
        return redirect()->back();
    }

    public function destroy(Project $project_id, StaticCost $additional_cost)
    {
        $additional_cost->photo && $this->file_delete($additional_cost->photo, 'documents/staticcosts/');
        $additional_cost->delete();
        return redirect()->back();
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
}
