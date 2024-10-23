<?php

namespace App\Exports\CicsaProcess;

use App\Models\CicsaAssignation;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class AssignationExport implements FromView, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        return view('Export.AssignationExport', [
            'title' => [
                'Fecha de Asignación',
                'Nombre de Proyecto',
                'Centro de Costos',
                'Cliente',
                'Codigo de Proyecto',
                'CPE',
                'Fecha fin',
                'Gestor',
                'Encargado'
            ],
            'assignations' => CicsaAssignation::all()
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 17,
            'B' => 100,
            'C' => 17,
            'D' => 17,
            'E' => 17,
            'F' => 17,
            'G' => 17,
            'H' => 17, 
            'I' => 30, 
        ];
    }
}
