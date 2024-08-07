<?php

namespace App\Exports\CicsaProcess;

use App\Models\CicsaAssignation;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class InstallationExport implements FromView, WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('Export.InstallationExport', [
            'title' => [
                'Nombre de Proyecto',
                'Codigo de Proyecto',
                'CPE',
                'Fecha PEXT',
                'Fecha PINT',
                'Monto Proyecto',
                'Acta de Conformidad',
                'Informe',
                'Jefe',
                'Liquidador',
                'SuperIntendente',
                'Encargado'
            ],
            'cicsa_purchase_order_validations' => CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe')
            ->with('cicsa_purchase_order_validation')
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
