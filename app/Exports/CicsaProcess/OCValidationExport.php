<?php

namespace App\Exports\CicsaProcess;

use App\Models\CicsaAssignation;
use App\Models\CicsaPurchaseOrder;
use App\Models\CicsaPurchaseOrderValidation;
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
                'Control de Materiales',
                'Supervisor',
                'Almacen',
                'Jefe',
                'Liquidador',
                'SuperIntendente',
                'Fecha de Validacion',
                'Encargado'
            ],
            'cicsa_purchase_order_validations' => CicsaPurchaseOrderValidation::with(['cicsa_purchase_order','cicsa_assignation' => function ($query) {
                $query->select('project_name','project_code','cpe')
            }])
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
