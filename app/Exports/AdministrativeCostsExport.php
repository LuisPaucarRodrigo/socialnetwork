<?php

namespace App\Exports;

use App\Models\AdministrativeCost;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class AdministrativeCostsExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $month_project_id;

    public function __construct($month_project_id)
    {
        $this->month_project_id = $month_project_id;
    }

    public function view():View
    {
        return view('Export/CostsAdministrativeExport', [
            'costs' => AdministrativeCost::with('month_project', 'provider')->where('month_project_id', $this->month_project_id)->get()
        ]);
    }
}
