<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\GangExpenseRequest\CreateGangExpenseRequest;
use App\Http\Requests\GangExpenseRequest\UpdateGangExpenseRequest;
use App\Models\Expense;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GangExpenseController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('get')) {
            return Inertia::render('Finance/GangExpense/Index', ['expenses' => Expense::paginate()]);
        } elseif ($request->isMethod('post')) {
            $searchQuery = $request->input('searchQuery');
            $expenses = Expense::where('person', 'like', "%$searchQuery%")
                ->orWhere('gang', 'like', "%$searchQuery%")
                ->orWhere('number', 'like', "%$searchQuery%")
                ->orWhere('series', 'like', "%$searchQuery%")
                ->orWhere('ruc', 'like', "%$searchQuery%")
                ->orWhere('type_expenses', 'like', "%$searchQuery%")
                ->paginate();

            return response()->json([
                'expenses' => $expenses
            ]);
        }
    }

    public function create()
    {
        return Inertia::render('Finance/GangExpense/CreateAndUpdate');
    }

    public function store(CreateGangExpenseRequest $request)
    {
        $validateData = $request->validated();
        Expense::create($validateData);
    }

    public function edit($id)
    {
        return Inertia::render('Finance/GangExpense/CreateAndUpdate', ['expenses' => Expense::find($id)]);
    }

    public function update(UpdateGangExpenseRequest $request, $id)
    {
        $validateData = $request->validated();
        $expense = Expense::findOrFail($id);
        $expense->update($validateData);
        return to_route('gangexpense.index');
    }
}
