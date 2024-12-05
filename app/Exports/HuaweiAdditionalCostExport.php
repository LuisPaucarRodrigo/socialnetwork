<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Models\HuaweiAdditionalCost;

class HuaweiAdditionalCostExport implements FromView
{
    protected $project_id;

    public function __construct($project_id)
    {
        $this->project_id = $project_id;
    }

    public function view():View
    {
        return view('Export/HuaweiAdditionalCostsExport', [
            'additional_costs' => HuaweiAdditionalCost::where('huawei_project_id', $this->project_id)->where('is_accepted', 1)->get()
        ]);
    }
}
