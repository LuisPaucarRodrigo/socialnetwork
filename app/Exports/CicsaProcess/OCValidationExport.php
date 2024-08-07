<?php

namespace App\Exports\CicsaProcess;

use App\Models\CicsaAssignation;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class OCValidationExport implements FromView, WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('Export.ValidationOcExport', [
            'title' => [
                'Nombre de Proyecto',
                'Codigo de Proyecto',
                'CPE',
                'Orden de Compra',
                'Fecha validada',
                'Control de Materiales',
                'Supervisor',
                'Almacen',
                'Jefe',
                'Liquidador',
                'SuperIntendente',
                'Encargado'
            ],
            'cicsa_purchase_order_validations' => CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe')
            ->with('cicsa_purchase_order_validation','cicsa_purchase_order')
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
            'J' => 17,
            'K' => 17,
            'L' => 30,
        ];
    }
}
