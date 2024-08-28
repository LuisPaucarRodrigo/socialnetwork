<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Models\HuaweiStaticCost;

class HuaweiStaticCostExport implements FromView
{
    protected $project_id;

    public function __construct($project_id)
    {
        $this->project_id = $project_id;
    }

    public function view():View
    {
        return view('Export/HuaweiStaticCostsExport', [
            'static_costs' => HuaweiStaticCost::where('huawei_project_id', $this->project_id)->get()
        ]);
    }
}
