<?php

namespace App\Exports\Payroll;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;


class PayrollDetailsExport implements FromView
{
    
    protected $data;
    protected $type;

    public function __construct($data, $type)
    {
        $this->data = $data;
        $this->type = $type;
    }

    public function view():View
    {
        $template = '';
        if($this->type === 'general') $template = 'PayrollIncomeDiscountTaxContributions';
        if($this->type === 'detail') $template = 'PayrollDetailsWithParams';
        return view("Export/Payroll/$template", $this->data);
    }
}
