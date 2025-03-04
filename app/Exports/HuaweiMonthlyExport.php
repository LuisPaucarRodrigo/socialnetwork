<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Models\HuaweiMonthlyExpense;

class HuaweiMonthlyExport implements FromView
{
    public function view():View
    {
        return view('Export/HuaweiMonthlyExport', [
            'expenses' => HuaweiMonthlyExpense::with('huawei_project.huawei_site')->where('is_accepted', 1)->get()
        ]);
    }
}
