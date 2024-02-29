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
        $currentDateUpdate = $currentDate->subHours(5);

        // Obtener todos los SubSection que están a punto de vencerse en los próximos 3 días y los que ya vencieron
        $purchases = Purchase_quote::where('quote_deadline', '<=', $currentDateUpdate->copy()->addDays(7)) // Ajustado para considerar los próximos 3 días y fechas pasadas
            ->whereHas('purchasing_requests', function($query) {
                $query->where('state', '!=', 'Aceptado');
            })    
            ->get(); // Obtener una colección de resultados

        $totalPurchases = $purchases->count();

        return response()->json([
            'totalPurchases' => $totalPurchases,
        ]);
    }
}
