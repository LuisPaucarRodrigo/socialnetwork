<?php

namespace App\Services\HumanResource;

use Illuminate\Http\Request;
use App\Constants\PayrollConstants;
use App\Constants\PintConstants;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\PayrollDetail;
use App\Models\PayrollDetailExpense;
use App\Models\Pension;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class PayrollDetailExpensesServices
{
    public function filter(Request $request, $query)
    {
        if ($request->search) {
            $searchTerms = $request->input('search');
            $query = $query->where(function ($q) use ($searchTerms) {
                $q->where('employee_name', 'like', "%$searchTerms%")
                    ->orWhereHas('general_expense', function($sq) use ($searchTerms) {
                        $sq->where('doc_number', 'like', "%$searchTerms%")
                        ->orWhere('operation_number', 'like', "%$searchTerms%")
                        ->orWhere('amount', 'like', "%$searchTerms%");
                    });
            });
        }
        if (filter_var($request->docNoDate, FILTER_VALIDATE_BOOLEAN)) {
            $query->whereHas('general_expense', function($sq){
                $sq->where('doc_date', null);
            });
        }
        if ($request->docStartDate) {
            $docStartDate = $request->docStartDate;
            $query->whereHas('general_expense', function($sq) use ($docStartDate){
                $sq->where('doc_date', '>=', $docStartDate);
            });
        }
        if ($request->docEndDate) {
            $docEndDate = $request->docEndDate;
            $query->whereHas('general_expense', function($sq) use ($docEndDate){
                $sq->where('doc_date', '<=', $docEndDate);
            });
        }
        if (filter_var($request->opNoDate, FILTER_VALIDATE_BOOLEAN)) {
            $query->whereHas('general_expense', function($sq){
                $sq->where('operation_date', null);
            });
        }
        if ($request->opStartDate) {
            $opStartDate = $request->opStartDate;
            $query->whereHas('general_expense', function($sq) use ($opStartDate){
                $sq->where('operation_date', '>=', $opStartDate);
            });
        }
        if ($request->opEndDate) {
            $opEndDate = $request->opEndDate;
            $query->whereHas('general_expense', function($sq) use ($opEndDate){
                $sq->where('operation_date', '<=', $opEndDate);
            });
        }

        if (count($request->selectedExpenseTypes) < PayrollConstants::countPayrollExpenseTypes()) {
            $selectedExpenseTypes = $request->selectedExpenseTypes;
            $query->whereHas('general_expense', function($sq) use ($selectedExpenseTypes){
                $sq->whereIn('expense_type', $selectedExpenseTypes);
            });
        }
        if (count($request->selectedDocTypes) < PayrollConstants::countPayrollDocTypes()) {
            $selectedDocTypes = $request->selectedDocTypes;
            $query->whereHas('general_expense', function($sq) use ($selectedDocTypes){
                $sq->whereIn('type_doc', $selectedDocTypes);
            });
        }
        $result = $query->get()->sortBy(function ($item) {
            return $item->general_expense->doc_date;
        });
        $result->transform(function ($item) {
            $item->payroll_detail->setAppends([]);
            $item->setAppends(['real_state']);
            return $item;
        });
        if (count($request->selectedStateTypes) < PintConstants::countScStatesTypes()) {
            $result = $result->filter(function ($item) use ($request) {
                return in_array($item->real_state, $request->selectedStateTypes);
            })->values()->all();
        }
        return $result;
    }

}