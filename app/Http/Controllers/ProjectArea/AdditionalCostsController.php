<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Project;
use App\Models\AdditionalCost;

class AdditionalCostsController extends Controller
{
    public function index(Project $project_id)
    {
        $additional_costs = AdditionalCost::where('project_id', $project_id->id)->with('project')->get();
        return Inertia::render('ProjectArea/ProjectManagement/AdditionalCosts', [
            'additional_costs' => $additional_costs,
            'project_id' => $project_id
        ]);
    }

    public function store(Project $project_id, Request $request)
    {
        $remaining_budget = $project_id->remaining_budget;
        
        $request->validate([
            'expense_type' => 'required|string|in:Combustible,Peaje,Otros,Combustible GEP',
            'ruc' => 'required|numeric|digits:11',
            'type_doc' => 'required|string|in:Deposito,Factura,Boleta,Voucher de Pago',
            'doc_number' => 'required|string',
            'doc_date' => 'required|date',
            'amount' => ['required', 'numeric', function($attribute, $value, $fail) use ($request, $remaining_budget){
                if($value > $remaining_budget){
                    $fail(__('El monto del gasto excede el presupuesto restante. S/. ' . number_format($remaining_budget, 2)));
            }}],
            'description' => 'required|string',
        ]);

        AdditionalCost::create([
            'expense_type' => $request->expense_type,
            'ruc' => $request->ruc,
            'type_doc' => $request->type_doc,
            'doc_number' => $request->doc_number,
            'doc_date' => $request->doc_date,
            'description' => $request->description,
            'amount' => $request->amount,
            'project_id' => $project_id->id
        ]);
        return redirect()->back(); 
    }
    

    public function update(AdditionalCost $additional_cost, Request $request)
    {
        $request->validate([
            'expense_type' => 'required|string|in:Combustible,Peaje,Otros,Combustible GEP',
            'ruc' => 'required|numeric|digits:11',
            'type_doc' => 'required|string|in:Deposito,Factura,Boleta,Voucher de Pago',
            'doc_number' => 'required|string',
            'doc_date' => 'required|date',
            'amount' => 'required|numeric',
            'description' => 'required|string',
        ]);

        $additional_cost->update([
            'expense_type' => $request->expense_type,
            'ruc' => $request->ruc,
            'type_doc' => $request->type_doc,
            'doc_number' => $request->doc_number,
            'doc_date' => $request->doc_date,
            'description' => $request->description,
            'amount' => $request->amount,
        ]);     
        return redirect()->back();
    }

    public function destroy(Project $project_id, AdditionalCost $additional_cost)
    {
        $additional_cost->delete();
        return to_route('projectmanagement.additionalCosts', ['project_id' => $project_id->id]);    
    }
}
