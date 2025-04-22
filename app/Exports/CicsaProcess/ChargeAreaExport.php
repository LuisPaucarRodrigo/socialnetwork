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
    protected $type;
    public function __construct($type)
    {
        $this->type = $type;
    }
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
            'cicsa_charge_areas' => CicsaAssignation::with([
                'project.cost_center',
                'cicsa_charge_area.cicsa_purchase_order:id,oc_number'
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
            'I' => 30,
            'J' => 30
        ];
    }
}
