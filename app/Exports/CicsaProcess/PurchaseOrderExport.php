<?php

namespace App\Exports\CicsaProcess;

use App\Models\CicsaAssignation;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class PurchaseOrderExport implements FromView, WithColumnWidths
{
    protected $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function view(): View
    {
        return view('Export.PurchaseOrderExport', [
            'purchaseOrders' => CicsaAssignation::with([
                'cicsa_purchase_order',
                'cicsa_installation:id,cicsa_assignation_id,projected_amount,shipping_report_date'
            ])
                ->whereHas('project', function ($subQuery) {
                    $subQuery->where('cost_line_id', $this->type);
                })
                ->get()
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 100,
            'B' => 17,
            'C' => 17,
            'D' => 17,
            'E' => 17,
            'F' => 17,
            'G' => 17,
            'H' => 17,
            'I' => 17,
            'J' => 30,
        ];
    }
}
