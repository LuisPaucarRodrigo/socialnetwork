<?php

namespace App\Exports\CicsaProcess;

use App\Models\CicsaAssignation;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class OCValidationExport implements FromView, WithColumnWidths
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
        return view('Export.ValidationOcExport', [
            'title' => [
                'Nombre de Proyecto',
                'Codigo de Proyecto',
                'Centro de Costos',
                'CPE',
                'Orden de Compra',
                'Monto de Orden de Compra',
                'Validacion de Expediente',
                'Control de Materiales',
                'Supervisor',
                'Almacen',
                'Jefe',
                'Liquidador',
                'SuperIntendente',
                'Observaciones',
                'Fecha de Validacion',
                'Encargado',
                'Gestor'
            ],
            'cicsa_purchase_order_validations' => CicsaAssignation::with([
                'project.cost_center',
                'cicsa_purchase_order_validation.cicsa_purchase_order:id,oc_number,amount',
            ])
                ->whereHas('project', function ($subQuery) {
                    $subQuery->where('cost_line_id', $this->type);
                })
                ->get()
            // 'cicsa_purchase_order_validations' => CicsaPurchaseOrderValidation::with([
            //     'cicsa_purchase_order' => function ($query) {
            //         $query->select('id', 'oc_number','amount');
            //     },
            //     'cicsa_assignation' => function ($query) {
            //         $query->select('id', 'project_name', 'project_code', 'cpe', 'manager');
            //     }
            // ])
            //     ->whereHas('cicsa_assignation', function ($iQuery) {
            //         $iQuery->whereHas('project', function ($subQuery) {
            //             $subQuery->where('cost_line_id', $this->type);
            //         });
            //     })
            //     ->get()
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
            'L' => 17,
            'M' => 30,
            'N' => 20,
            'O' => 30,
            'P' => 30
        ];
    }
}
