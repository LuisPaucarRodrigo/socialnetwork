<?php

namespace App\Exports\Payroll;

use App\Models\Contract;
use App\Models\PayrollDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;


class PayrollExport implements FromView, WithColumnWidths
{
    protected $payroll_id;

    public function __construct($payroll_id)
    {
        $this->payroll_id = $payroll_id;
    }

    public function view(): View
    {   
        return view('Export.PayrollExport', [
            'spreadsheets' => PayrollDetail::with('employee', 'pension')->where('payroll_id',$this->payroll_id)->get()
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 17,
            'B' => 17,
            'C' => 17,
            'D' => 17,
            'E' => 17,
            'F' => 17,
            'G' => 17,
            'H' => 17,
            'I' => 17,
            'J' => 17,
            'K' => 17,
            'L' => 17,
            'M' => 17,
            'N' => 17,
            'O' => 17,
            'P' => 17,
            'Q' => 17,
            'R' => 17,
            'S' => 17,
            'T' => 17,
            'U' => 17,
            'V' => 17,
            'W' => 17,
        ];        
    }
}
