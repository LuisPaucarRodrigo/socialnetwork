<?php

namespace App\Exports\CicsaProcess;

use App\Models\CicsaAssignation;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CicsaProcessExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function view(): View
    {
        return view('Export.CicsaProcessExport', [
            'title' => [
                'Fecha de Asignación',
                'Nombre de Proyecto',
                'Centro de Costos',
                'Cliente',
                'Codigo de Proyecto',
                'CPE',
                'Gestor',
                'Encargado',

                'Fecha Factibilidad',
                'Informe',
                'Coordinador',
                'Encargado',

                'Fecha de recojo',
                'Numero de Guia',
                'Encargado CCIP',

                'Fecha PEXT',
                'Fecha PINT',
                'Acta de Conformidad',
                'Informe',
                'Fecha de Envio de Informe',
                'Monto Proyecto',
                'Coordinador',
                'Encargado',

                'Fecha de OC',
                'Numero de OC',
                'Formato Maestro',
                'Item 3456',
                'Presupuesto',
                'Encargado',

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

                'Fecha de Servicio de Orden',
                'Servicio de Orden',
                'Hoja de Estimacion',
                'Orden de Compra',
                'PDF Factura',
                'ZIP Factura',
                'Encargado',

                'Numero de Factura',
                'Fecha Factura',
                'Credito',
                'Fecha de Deposito',
                'Monto',
                'Encargado'
            ],
            'cicsaProcess' => CicsaAssignation::with('cicsa_feasibility', 'cicsa_installation', 'cicsa_materials', 'cicsa_purchase_order', 'cicsa_purchase_order_validation', 'cicsa_service_order', 'cicsa_charge_area')
                ->get()
        ]);
    }
}
