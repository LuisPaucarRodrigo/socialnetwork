<?php

namespace App\Exports\CicsaProcess;

use App\Models\CicsaAssignation;
use App\Models\CicsaServiceOrder;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ServiceOrderExport implements FromView, WithColumnWidths
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
        return view('Export.ServiceOrderExport', [
            'title' => [
                'Nombre de Proyecto',
                'Centro de Costos',
                'CPE',
                'Orden de Compra',
                'Fecha de Servicio de Orden',
                'Servicio de Orden',
                'Hoja de Estimacion',
                'Orden de Compra',
                'PDF Factura',
                'ZIP Factura',
            ],
            'cicsa_service_orders' => CicsaAssignation::with([
                'project.cost_center',
                'cicsa_service_order.cicsa_purchase_order:id,oc_number',
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
            'I' => 17,
            'J' => 17,
        ];
    }
}
