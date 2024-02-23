<?php

namespace App\Http\Controllers\Finance;


use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Deposit;
use App\Models\AccountingAccount;


class DepositController extends Controller
{
    public function deposits_index(){
        return Inertia::render("Finance/Deposits/Deposits", [
            "deposits" => Deposit::with('accounting_account')
                ->whereYear('operation_date', now()->year)
                ->whereMonth('operation_date', now()->month)
                ->orderBy('operation_date', 'desc')
                ->orderBy('id', 'desc')
                ->get(),
            'accounts'=>AccountingAccount::all()
        ]);
    }


    public function deposits_store(Request $request, $deposit_id=null) {
        $data = $request->validate([
            'operation_date'=>'required',
            'operation_code'=>'nullable|unique:deposits,operation_code',
            'operation_description'=>'nullable',
            'denomination'=>'required',
            'observation'=>'nullable',
            'comission'=>'nullable',
            'total_type'=>'required',
            'total'=>'required',
            'accounting_account_id'=>'required',
        ]);
        $deposit_id ? Deposit::find($deposit_id)->update($data)
                    : Deposit::create($data);
        return redirect()->back();
    }
}
