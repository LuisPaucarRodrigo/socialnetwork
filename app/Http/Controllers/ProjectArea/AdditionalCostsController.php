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
        $additional_costs = AdditionalCost::where('project_id', $project_id->id)->with('project')->get();
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
            'expense_type' => 'required|string|in:Combustible,Peaje,Otros,Combustible GEP',
            'ruc' => ['required', 'numeric', function ($attribute, $value, $fail) {
                if (!(strlen($value) == 11 || strlen($value) == 8)) {
                    $fail('Se debe tener exactamente 8 o 11 dígitos.');
                }
            }],
            'type_doc' => 'required|string|in:Deposito,Factura,Boleta,Voucher de Pago',
            'doc_number' => 'nullable|string',
            'doc_date' => 'required|date',
            'amount' => ['required', 'numeric', function($attribute, $value, $fail) use ($request, $remaining_budget){
                if($value > $remaining_budget){
                    $fail(__('El monto del gasto excede el presupuesto restante. S/. ' . number_format($remaining_budget, 2)));
            }}],
            'zone'=>'required',
            'provider_id'=> 'nullable',
            'description' => 'required|string',
        ]);
        AdditionalCost::create($data);
        return redirect()->back(); 
    }
    

    public function update(AdditionalCost $additional_cost, Request $request)
    {
        $data = $request->validate([
            'expense_type' => 'required|string|in:Combustible,Peaje,Otros,Combustible GEP',
            'ruc' => ['required', 'numeric', function ($attribute, $value, $fail) {
                if (!(strlen($value) == 11 || strlen($value) == 8)) {
                    $fail('Se debe tener exactamente 8 o 11 dígitos.');
                }
            }],
            'type_doc' => 'required|string|in:Deposito,Factura,Boleta,Voucher de Pago',
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
}
