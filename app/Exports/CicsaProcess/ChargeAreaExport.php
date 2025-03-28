<?php

namespace App\Exports\CicsaProcess;

use App\Models\CicsaAssignation;
use App\Models\CicsaChargeArea;
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
                'Centro de Costos',
                'CPE',
                'Numero de OC',
                'Numero de Factura',
                'Fecha Factura',
                'Credito',
                'Fecha de Deposito',
                'Monto',
                'Encargado'
            ],
            'cicsa_charge_areas' => CicsaChargeArea::with([
                'cicsa_assignation:id,project_name,project_code,cpe,project_id',
                'cicsa_assignation.project:id,cost_center_id',
                'cicsa_assignation.project.cost_center',
                'cicsa_purchase_order:id,oc_number'
            ])
                ->whereHas('cicsa_assignation.project', function ($subQuery) {
                    $subQuery->where('cost_line_id', 2);
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
            'I' => 30,
            'J' => 30
        ];
    }
}
