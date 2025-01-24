<?php

namespace App\Http\Controllers\Finance;

use App\Constants\PintConstants;
use App\Http\Controllers\Controller;
use App\Models\GeneralExpense;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SunatController extends Controller
{
    public function general_expenses(Request $request)
    {
        //current month, poner
        $month = $request->month;
        if ($month) {
            $currentMonth = Carbon::parse($month)->month;
            $currentYear = Carbon::parse($month)->year;
        } else {
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
        }
        $data = GeneralExpense::whereDoesntHave('payroll_detail_expense')
            ->where('type_doc', PintConstants::FACTURA)
            ->where(function ($query) use ($currentMonth, $currentYear) {
                $query->whereMonth('doc_date', $currentMonth)
                    ->whereYear('doc_date', $currentYear);
            })
            ->get();
        $data->each(function ($item) {
            $item->makeHidden(['additional_cost', 'static_cost', 'pext_project_expense', 'payroll_detail_expense', 'huawei_monthly_expense']);
        });
        return response()->json(["data" => $data]);
    }
}
