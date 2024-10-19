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
                'Centro de Costos',
                'CPE',
                'Fecha PEXT',
                'Fecha PINT',
                'Acta de Conformidad',
                'Informe',
                'Fecha de Envio de Informe',
                'Monto Proyecto',
                'Coordinador',
                'Encargado'
            ],
            'assignations' => CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe', 'cost_center')
            ->with('cicsa_installation')
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
            'G' => 30,
            'H' => 30,
            'I' => 30,
            'J' => 30,
            'K' => 30,
            'L' => 30
        ];
    }
}
