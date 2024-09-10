<?php

namespace App\Exports;

use App\Models\StaticCost;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;


class StaticCostsExport implements FromView
{
    protected $project_id;

    public function __construct($project_id)
    {
        $this->project_id = $project_id;
    }

    public function view():View
    {
        return view('Export/CostsExport', [
            'costs' => StaticCost::with('project', 'provider')->where('project_id', $this->project_id)->get()
        ]);
    }
}
