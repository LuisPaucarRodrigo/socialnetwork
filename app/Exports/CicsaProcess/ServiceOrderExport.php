<?php

namespace App\Exports\CicsaProcess;

use App\Models\CicsaAssignation;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ServiceOrderExport implements FromView, WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('Export.ServiceOrderExport', [
            'title' => [
                'Nombre de Proyecto',
                'Codigo de Proyecto',
                'CPE',
                'Orden de Compra',
                'Fecha de Servicio de Orden',
                'Servicio de Orden',
                'Hoja de Estimacion',
                'Orden de Compra',
                'PDF Factura',
                'ZIP Factura',
                'Encargado',
            ],
            'cicsa_service_orders' => CicsaAssignation::select('id', 'project_name', 'project_code', 'cpe')
            ->with('cicsa_service_order','cicsa_purchase_order')
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
