<?php

namespace App\Exports\Payroll;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class PayrollBankExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view("Export/Payroll/PayrollBankTable", [
            'data' => $this->data
        ]);
    }
}
