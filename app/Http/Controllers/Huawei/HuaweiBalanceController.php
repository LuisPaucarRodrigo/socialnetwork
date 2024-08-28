<?php

namespace App\Http\Controllers\Huawei;

use App\Http\Controllers\Controller;
use App\Models\HuaweiBalanceEarning;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HuaweiBalanceController extends Controller
{
    public function getSummary ()
    {

    }

    //earnings

    public function getEarnings ()
    {
        return Inertia::render('Huawei/BalanceEarnings', [
            'earnings' => HuaweiBalanceEarning::paginate(10)
        ]);
    }

    public function storeEarnings (Request $request)
    {
        $data = $request->validate([
            'invoice_number' => 'required',
            'amount' => 'required',
            'invoice_date' => 'required',
            'deposit_date' => 'nullable'
        ]);

        HuaweiBalanceEarning::create($data);

        return redirect()->back();
    }

    public function updateEarnings (HuaweiBalanceEarning $huawei_balance_earning, Request $request)
    {
        $data = $request->validate([
            'invoice_number' => 'required',
            'amount' => 'required',
            'invoice_date' => 'required',
            'deposit_date' => 'nullable'
        ]);

        $huawei_balance_earning->update($data);

        return redirect()->back();
    }

    public function deleteEarning (HuaweiBalanceEarning $huawei_balance_earning)
    {
        $huawei_balance_earning->delete();

        return redirect()->back();
    }
}

