<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Models\HuaweiProjectRealEarning;

class HuaweiProjectRealEarningsExport implements FromView
{
    protected $project_id;

    public function __construct($project_id)
    {
        $this->project_id = $project_id;
    }

    public function view():View
    {
        return view('Export/HuaweiRealEarningsExport', [
            'real_earnings' => HuaweiProjectRealEarning::where('huawei_project_id', $this->project_id)->get()
        ]);
    }
}
