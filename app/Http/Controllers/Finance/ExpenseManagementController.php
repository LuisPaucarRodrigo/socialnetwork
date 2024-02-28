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
    public function index(){
        return Inertia::render('Finance/ManagementExpense/Expense', ['expenses' => Purchase_quote::with('purchasing_requests.project')->paginate()]);
    }


    public function reviewed(ReviewedExpenseRequest $request, $id) {
        $data = $request->validated();
        Purchase_quote::find($id)->update($data);
        $date_issue = Carbon::today();
        if($data['state']){
            Purchase_order::create([
                'date_issue' => $date_issue,
                'purchase_quote_id' => $id
            ]);
        }
        return redirect()->back();

        // $purchaseQuote = Purchase_quote::with('purchasing_requests')->find($id);
        // if ($request->state == "Aceptado") {
        //     $date_issue = Carbon::today();
        //     Purchase_order::create([
        //         'date_issue' => $date_issue,
        //         'purchase_quote_id' => $id
        //     ]);
        //     $otherQuotes = Purchase_quote::where('id', '!=', $id)
        //         ->whereHas('purchasing_requests', function ($query) use ($purchaseQuote) {
        //             $query->where('purchasing_request_id', $purchaseQuote->purchasing_request_id);
        //         })
        //         ->get();
        //     foreach ($otherQuotes as $otherQuote) {
        //         $otherQuote->delete();
        //     }

        //     $purchaseQuote->purchasing_requests->update(['state' => 'Aceptado']);
        // } else {
        //     $purchaseQuote->delete();
        // }
        // return redirect()->route('managementexpense.index');
    }

    public function doTask()
    {
        // Obtener la fecha actual ajustada por el desfase
        $currentDate = Carbon::now();

        // Obtener todos los Purchase_quote dentro del rango de 0 a 3 días y cuyo estado sea 0 o nulo
        $purchasesLessThanThreeDays = Purchase_quote::where('quote_deadline', '<=', $currentDate->copy()->addDays(3))
            ->where(function($query) {
                $query->where('state', '=', 0)
                    ->orWhereNull('state');
            })
            ->with('purchasing_requests')
            ->get();

        $totalPurchasesLessThanThreeDays = $purchasesLessThanThreeDays->count();

        // Obtener todos los Purchase_quote dentro del rango de 4 a 7 días y cuyo estado sea 0 o nulo
        $purchasesBetweenFourAndSevenDays = Purchase_quote::where('quote_deadline', '>=', $currentDate->copy()->addDays(3))
            ->where('quote_deadline', '<=', $currentDate->copy()->addDays(7))
            ->where(function($query) {
                $query->where('state', '=', 0)
                    ->orWhereNull('state');
            })
            ->with('purchasing_requests')
            ->get();

        $totalPurchasesBetweenFourAndSevenDays = $purchasesBetweenFourAndSevenDays->count();

        return response()->json([
            'purchasesLessThanThreeDays' => $purchasesLessThanThreeDays,
            'totalPurchasesLessThanThreeDays' => $totalPurchasesLessThanThreeDays,
            'purchasesBetweenFourAndSevenDays' => $purchasesBetweenFourAndSevenDays,
            'totalPurchasesBetweenFourAndSevenDays' => $totalPurchasesBetweenFourAndSevenDays,
        ]);
    }

}
