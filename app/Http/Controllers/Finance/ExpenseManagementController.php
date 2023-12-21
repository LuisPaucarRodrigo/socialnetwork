<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Purchase_order;
use App\Models\Purchase_quote;
use App\Models\Purchasing_request;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExpenseManagementController extends Controller
{
    public function index()
    {
        return Inertia::render('Finance/ManagementExpense/Expense', ['expenses' => Purchase_quote::with('purchasing_requests.project')->paginate()]);
    }

    public function details($id)
    {
        return Inertia::render('Finance/ManagementExpense/ExpenseDetails', ['details' => Purchase_quote::with('purchasing_requests.project')->find($id)]);
    }

    public function reviewed(Request $request, $id)
    {   
        //dd($request->all());
        $request->validate([
            'state' => 'required|string|in:Aceptado,Rechazado'
        ]);
        $purchasing = Purchasing_request::find($id);
        $purchasing->update([
            'state' => $request->state,
            'response' => $request->coment
        ]);
        if ($request->state == 'Aceptado') {
            $date_issue = Carbon::today();
            Purchase_order::create([
                'date_issue' => $date_issue,
                'purchase_quote_id' => $id
            ]);
        };

        return to_route('managementexpense.index');
    }
}
