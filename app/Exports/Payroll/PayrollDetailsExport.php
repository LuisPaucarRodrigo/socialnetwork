<?php

namespace App\Exports\Payroll;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;


class PayrollDetailsExport implements FromView
{
    
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view():View
    {
        return view('Export/Payroll/PayrollIncomeDataTaxContributions', $this->data);
    }
}
