<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Project;
use App\Models\AdditionalCost;

class AdditionalCostsController extends Controller
{
    public function index(Project $project_id)
    {
        $additional_costs = AdditionalCost::where('project_id', $project_id->id)->with('project', 'provider')->paginate(20);
        $providers = Provider::all();
        return Inertia::render('ProjectArea/ProjectManagement/AdditionalCosts', [
            'additional_costs' => $additional_costs,
            'project_id' => $project_id,
            'providers' => $providers
        ]);
    }

    public function store(Project $project_id, Request $request)
    {
        $remaining_budget = $project_id->remaining_budget;
        
        $data = $request->validate([
            'expense_type' => 'required|string',
            'ruc' =>'required|numeric|digits:11',
            'type_doc' => 'required|string|in:Efectivo,Deposito,Factura,Boleta,Voucher de Pago',
            'doc_number' => 'nullable|string',
            'doc_date' => 'required|date',
            'amount' => ['required', 'numeric', function($attribute, $value, $fail) use ($request, $remaining_budget){
                if($value > $remaining_budget){
                    $fail(__('El monto del gasto excede el presupuesto restante. S/. ' . number_format($remaining_budget, 2)));
            }}],
            'zone'=>'required',
            'provider_id'=> 'nullable',
            'description' => 'required|string',
            'photo' => 'nullable',
            'project_id'=> 'required'
        ]);
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->file_store($request->file('photo'), 'documents/additionalcosts/');
        }
        
        AdditionalCost::create($data);
        return redirect()->back(); 
    }

    public function download_ac_photo(AdditionalCost $additional_cost_id)
    {
        $fileName = $additional_cost_id->photo;
        $filePath = 'documents/additionalcosts/' . $fileName;
        $path = public_path($filePath);
        if (file_exists($path)) {
            ob_end_clean();
            return response()->file($path, ['title' => $fileName]);
        }
        abort(404, 'Documento no encontrado');
    }
    

    public function update( Request $request, AdditionalCost $additional_cost)
    {
        $data = $request->validate([
            'expense_type' => 'required|string',
            'ruc' => 'required|numeric|digits:11',
            'type_doc' => 'required|string|in:Efectivo,Deposito,Factura,Boleta,Voucher de Pago',
            'doc_number' => 'nullable|string',
            'doc_date' => 'required|date',
            'amount' => 'required|numeric',
            'zone'=>'required',
            'provider_id'=> 'nullable',
            'description' => 'required|string',
        ]);

        $additional_cost->update($data);     
        return redirect()->back();
    }

    public function destroy(Project $project_id, AdditionalCost $additional_cost)
    {
        $additional_cost->delete();
        return to_route('projectmanagement.additionalCosts', ['project_id' => $project_id->id]);    
    }


    public function file_store($file, $path)
    {
        $name = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path($path), $name);
        return $name;
    }
}
