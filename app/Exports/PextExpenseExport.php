<?php

namespace App\Exports;

use App\Models\PextProjectExpense;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class PextExpenseExport implements FromView, WithColumnWidths
{
    protected $pext_project_id;
    protected $start_date;
    protected $end_date;

    public function __construct($pext_project_id, $start_date = null, $end_date = null)
    {
        $this->pext_project_id = $pext_project_id;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function view(): View
    {
        if ($this->pext_project_id) {
            return view('Export/PextExpenseExport', [
                'expenses' => PextProjectExpense::with(['provider:id,company_name', 'cicsa_assignation:id,project_name,cost_center'])
                    ->where('pext_project_id', $this->pext_project_id)
                    ->where('is_accepted', 1)
                    ->get()
            ]);
        } else {
            return view('Export/PextExpenseExport', [
                'expenses' => PextProjectExpense::with(['provider:id,company_name', 'cicsa_assignation:id,project_name,cost_center'])
                    ->whereBetween('operation_date', [$this->start_date, $this->end_date])
                    ->where('is_accepted', 1)
                    ->get()
            ]);
        }
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
            'N' => 30
        ];
    }
}
