<?php

namespace App\Exports\CicsaProcess;

use App\Models\CicsaAssignation;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ChargeAreaExport implements FromView, WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('Export.ChargeAreaExport', [
            'title' => [
                'Nombre de Proyecto',
                'Codigo de Proyecto',
                'CPE',
                'Numero de Factura',
                'Fecha Factura',
                'Credito',
                'Fecha de Deposito',
                'Monto',
                'Encargado'
            ],
            'cicsa_charge_areas' => CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe')
            ->with('cicsa_charge_area','cicsa_purchase_order')
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
            'F' => 30,
            'G' => 30,
        ];
    }
}
