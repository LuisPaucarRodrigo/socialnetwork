<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseRequest\ReviewedExpenseRequest;
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

    public function reviewed(ReviewedExpenseRequest $request, $id)
    {
        $purchaseQuote = Purchase_quote::with('purchasing_requests')->find($id);
        if ($request->state == "Aceptado") {
            $date_issue = Carbon::today();
            Purchase_order::create([
                'date_issue' => $date_issue,
                'purchase_quote_id' => $id
            ]);
            $otherQuotes = Purchase_quote::where('id', '!=', $id)
                ->whereHas('purchasing_requests', function ($query) use ($purchaseQuote) {
                    $query->where('purchasing_request_id', $purchaseQuote->purchasing_request_id);
                })
                ->get();
            foreach ($otherQuotes as $otherQuote) {
                $otherQuote->delete();
            }

            $purchaseQuote->purchasing_requests->update(['state' => 'Aceptado']);
        } else {
            $purchaseQuote->delete();
        }
        return redirect()->route('managementexpense.index');
    }
}
