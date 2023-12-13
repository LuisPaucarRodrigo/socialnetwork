<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Purchase_order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExpenseManagementController extends Controller
{
    public function index () {
        return Inertia::render('Finance/ManagementExpense/Expense',['expenses' => Purchase_order::paginate()]);
    }
    public function details ($id) {
        return Inertia::render('Finance/ManagementExpense/ExpenseDetails',['details' => Purchase_order::with('provider')->find($id)]);
    }
}
