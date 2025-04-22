<?php

namespace App\Exports;

use App\Models\PextProjectExpense;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class PextExpenseExport implements FromView, WithColumnWidths
{
    protected $cost_line;
    protected $project_id;
    protected $fixedOrAdditional;

    public function __construct($cost_line, $project_id, $fixedOrAdditional)
    {
        $this->cost_line = $cost_line;
        $this->project_id = $project_id;
        $this->fixedOrAdditional = $fixedOrAdditional;
    }

    public function view(): View
    {
        $expense = null;
        if ($this->project_id) {
            $expense = PextProjectExpense::with(['provider:id,company_name'])
                ->where('project_id', $this->project_id)
                ->where('fixedOrAdditional', $this->fixedOrAdditional)
                ->where('is_accepted', 1)
                ->get();
        } else {
            $expense = PextProjectExpense::with(['provider:id,company_name'])
                ->whereHas('project', function ($e){
                    $e->where('cost_line_id', $this->cost_line);
                })
                ->where('project_id', "!=", 320)
                ->where('fixedOrAdditional', $this->fixedOrAdditional)
                ->where('is_accepted', 1)
                ->get();
        }

        return view('Export/PextExpenseExport', [
            'expenses' => $expense
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
            'L' => 30,
        ];
    }
}
