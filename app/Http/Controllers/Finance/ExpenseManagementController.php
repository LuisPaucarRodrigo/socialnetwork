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
    public function index(Request $request, $boolean = false)
    {
        if ($request->isMethod('get')) {
            return Inertia::render('Finance/ManagementExpense/Expense', [
                'expenses' => Purchase_quote::with('provider', 'purchasing_requests.project')
                    ->whereHas('purchasing_requests', function ($query) {
                        $query->whereNotNull('due_date');
                    })
                    ->whereNotNull('quote_deadline')
                    ->where('state', $boolean ? 1 : null)
                    ->paginate(),
                'boolean' => boolval($boolean)
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
        $expense = $purchase_quote->load('purchasing_requests.project', 'purchasing_requests.preproject', 'purchase_order', 'provider', 'products');
        return Inertia::render('Finance/ManagementExpense/Details', ['expense' => $expense]);
    }

    public function reviewed(ReviewedExpenseRequest $request, $id)
    {
        $data = $request->validated();
        $quote = Purchase_quote::find($id);
        $quote->update($data);
        $date_issue = Carbon::today();
        if ($data['state']) {
            foreach ($data['payments'] as $paymentData) {
                $register_date = $quote->payment_type == 'Contado' ? $this->calculateRegisterDate() : $paymentData['register_date'];
                Payment::create([
                    'amount' => $paymentData['amount'],
                    'description' => $paymentData['description'],
                    'register_date' => $register_date,
                    'purchase_quote_id' => $id
                ]);
            }
            Purchase_order::create([
                'date_issue' => $date_issue,
                'purchase_quote_id' => $id
            ]);
        }
    }

    public function approve_quote_alarm()
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

        // Obtener todos los Purchase_quote dentro del rango de 4 a 7 días y cuyo estado sea 0 o nulo
        $purchasesBetweenFourAndSevenDays = Purchase_quote::where('quote_deadline', '>=', $currentDate->copy()->addDays(3))
            ->where('quote_deadline', '<=', $currentDate->copy()->addDays(7))
            ->where(function ($query) {
                $query->where('state', 2)
                    ->orWhereNull('state');
            })
            ->with('purchasing_requests')
            ->get();

        return response()->json([
            'purchasesLessThanThreeDays' => $purchasesLessThanThreeDays,
            'purchasesBetweenFourAndSevenDays' => $purchasesBetweenFourAndSevenDays
        ]);
    }

    public function generate_payment($id)
    {
        return Inertia::render('Finance/ManagementExpense/GeneratePayments', [
            'quote' => Purchase_quote::find($id)
        ]);
    }

    private function calculateRegisterDate()
    {
        $currentHour = Carbon::now()->hour;

        if ($currentHour >= 17) {
            return Carbon::tomorrow();
        } else {
            return Carbon::today();
        }
    }
}
