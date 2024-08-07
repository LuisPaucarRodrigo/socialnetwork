<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Models\HuaweiProjectEarning;



class HuaweiProjectEarningsExport implements FromView
{
    protected $project_id;

    public function __construct($project_id)
    {
        $this->project_id = $project_id;
    }

    public function view():View
    {
        return view('Export/HuaweiEarningsExport', [
            'earnings' => HuaweiProjectEarning::where('huawei_project_id', $this->project_id)->get()
        ]);
    }
}
