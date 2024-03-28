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
        $request->validate([
            'expense_type' => 'required|string|in:Combustible,Peaje,Otros,Combustible GEP',
            'ruc' => 'required|numeric|digits:11',
            'type_doc' => 'required|string|in:Deposito,Factura,Boleta,Voucher de Pago',
            'invoice_number' => 'required|string',
            'invoice_date' => 'required|date',
            'amount' => 'required|numeric',
            'description' => 'required|string',
        ]);

        AdditionalCost::create([
            'expense_type' => $request->expense_type,
            'ruc' => $request->ruc,
            'type_doc' => $request->type_doc,
            'invoice_number' => $request->invoice_number,
            'invoice_date' => $request->invoice_date,
            'description' => $request->description,
            'amount' => $request->amount,
            'project_id' => $project_id->id
        ]);       
    }

    public function update(Project $project_id, AdditionalCost $additional_cost, Request $request)
    {
        $request->validate([
            'expense_type' => 'required|string|in:Combustible,Peaje,Otros,Combustible GPE',
            'ruc' => 'required|numeric|digits:11',
            'type_doc' => 'required|string|in:Deposito,Factura,Boleta,Voucher de Pago',
            'invoice_number' => 'required|string',
            'invoice_date' => 'required|date',
            'amount' => 'required|numeric',
            'description' => 'required|string',
        ]);

        $additional_cost->update([
            'expense_type' => $request->expense_type,
            'ruc' => $request->ruc,
            'type_doc' => $request->type_doc,
            'invoice_number' => $request->invoice_number,
            'invoice_date' => $request->invoice_date,
            'description' => $request->description,
            'amount' => $request->amount,
        ]);     
    }

    public function destroy(Project $project_id, AdditionalCost $additional_cost)
    {
        $additional_cost->delete();
        return to_route('projectmanagement.additionalCosts', ['project_id' => $project_id->id]);    
    }
}
