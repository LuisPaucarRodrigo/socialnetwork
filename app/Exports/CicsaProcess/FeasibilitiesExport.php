<?php

namespace App\Exports\CicsaProcess;

use App\Models\CicsaAssignation;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class FeasibilitiesExport implements FromView, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        return view('Export.FeasibilityExport', [
            'title' => [
                'Nombre de Proyecto',
                'Codigo de Proyecto',
                'CPE',
                'Fecha Factibilidad',
                'Informe',
                'Coordinador',
                'Encargado'
            ],
            'feasibilitys' => CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe')
                ->with('cicsa_feasibility')
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
