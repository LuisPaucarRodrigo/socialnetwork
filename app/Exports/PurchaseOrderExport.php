<?php

namespace App\Exports;

use App\Models\CicsaAssignation;
use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class PurchaseOrderExport implements FromView, WithColumnWidths
{
    public function view(): View
    {
        return view('Export.PurchaseOrderExport', [
            'purchaseOrders' => CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe')
                ->with('cicsa_purchase_order')
                ->get()
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 50,
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
