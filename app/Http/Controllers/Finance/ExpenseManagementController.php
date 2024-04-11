<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseRequest\ReviewedExpenseRequest;
use App\Models\Payment;
use App\Models\Purchase_order;
use App\Models\Purchase_quote;
use App\Models\Purchasing_request;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExpenseManagementController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('get')) {
            return Inertia::render('Finance/ManagementExpense/Expense', [
                'expenses' => Purchase_quote::with('provider', 'purchasing_requests.project')
                    ->whereHas('purchasing_requests', function ($query) {
                        $query->whereNotNull('due_date');
                    })
                    ->whereNotNull('quote_deadline')
                    ->paginate()
            ]);
        } elseif ($request->isMethod('post')) {
            $searchQuery = $request->input('searchQuery');
            $expenses = Purchase_quote::with('provider', 'purchasing_requests.project.preproject')
                ->whereNotNull('quote_deadline')
                ->whereHas('purchasing_requests', function ($query) use ($searchQuery) {
                    $query->whereNotNull('due_date')
                        ->where(function ($q) use ($searchQuery) {
                            $q->where('title', 'like', "%$searchQuery%");
                        })
                        ->orWhereHas('project.preproject', function ($q) use ($searchQuery) {
                            $q->where('code', 'like', "%$searchQuery%");
                        });
                })
                ->paginate();
            return response()->json([
                'expenses' => $expenses
            ]);
        }
    }

    public function details(Purchase_quote $purchase_quote)
    {
        $expense = $purchase_quote->load('purchasing_requests.project','purchasing_requests.preproject', 'purchase_order', 'provider', 'products', 'purchase_quote_products');
        return Inertia::render('Finance/ManagementExpense/Details', ['expense' => $expense]);
    }

    public function reviewed(ReviewedExpenseRequest $request, $id)
    {
        $data = $request->validated();
        Purchase_quote::find($id)->update($data);
        $date_issue = Carbon::today();
        if ($data['state']) {
            foreach ($data['payments'] as $paymentData) {
                Payment::create([
                    'amount' => $paymentData['amount'],
                    'description' => $paymentData['description'],
                    'purchase_quote_id' => $id
                ]);
            }
            Purchase_order::create([
                'date_issue' => $date_issue,
                'purchase_quote_id' => $id
            ]);
        }
    }

    public function doTask()
    {
        // Obtener la fecha actual ajustada por el desfase
        $currentDate = Carbon::now();

        // Obtener todos los Purchase_quote dentro del rango de 0 a 3 días y cuyo estado sea 0 o nulo
        $purchasesLessThanThreeDays = Purchase_quote::where('quote_deadline', '<=', $currentDate->copy()->addDays(3))
            ->where(function ($query) {
                $query->where('state', 2)
                    ->orWhereNull('state');
            })
            ->with('purchasing_requests')
            ->get();


        $totalPurchasesLessThanThreeDays = $purchasesLessThanThreeDays->count();

        // Obtener todos los Purchase_quote dentro del rango de 4 a 7 días y cuyo estado sea 0 o nulo
        $purchasesBetweenFourAndSevenDays = Purchase_quote::where('quote_deadline', '>=', $currentDate->copy()->addDays(3))
            ->where('quote_deadline', '<=', $currentDate->copy()->addDays(7))
            ->where(function ($query) {
                $query->where('state', 2)
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

    public function generate_payment($id)
    {
        return Inertia::render('Finance/ManagementExpense/GeneratePayments', [
            'quote' => Purchase_quote::find($id)
        ]);
    }
}
