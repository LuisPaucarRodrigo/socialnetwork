<?php

namespace App\Exports;

use App\Models\HuaweiBalanceCost;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class HuaweiGeneralCostsExport implements FromView
{
    public function view():View
    {
        return view('Export/HuaweiGeneralCostsExport', [
            'general_costs' => HuaweiBalanceCost::get()
        ]);
    }
}
