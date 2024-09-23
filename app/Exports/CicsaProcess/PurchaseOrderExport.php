<?php

namespace App\Exports\CicsaProcess;

use App\Models\CicsaAssignation;
use App\Models\CicsaPurchaseOrder;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class PurchaseOrderExport implements FromView, WithColumnWidths
{
    public function view(): View
    {
        return view('Export.PurchaseOrderExport', [
            'purchaseOrders' => CicsaPurchaseOrder::with(['cicsa_assignation' => function ($query) {
                $query->select('id', 'project_name', 'project_code', 'cpe');
            }])
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
