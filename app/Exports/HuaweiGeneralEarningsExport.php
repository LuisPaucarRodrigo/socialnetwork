<?php

namespace App\Exports;

use App\Models\HuaweiBalanceEarning;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class HuaweiGeneralEarningsExport implements FromView
{
    public function view():View
    {
        return view('Export/HuaweiGeneralEarningsExport', [
            'general_earnings' => HuaweiBalanceEarning::get()
        ]);
    }
}
