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
    protected $stages;
    public function __construct($stages)
    {
        $this->stages = $stages;
    }
    public function view(): View
    {   
        $titleBase = [
            'Fecha de Asignación',
            'Nombre de Proyecto',
            'Centro de Costos',
            'Cliente',
            'Codigo de Proyecto',
            'CPE',
        ];
        $titleProject = [
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
        ];
        $titleAdministration = [
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
        ];
        $titleOCNumber = [
            'Numero de OC',
        ];

        $titleCobranza = [
            'Numero de Factura',
            'Fecha Factura',
            'Credito',
            'Fecha de Deposito',
            'Monto',
            'Encargado'
        ];
        $title = [];
        $stageExport = CicsaAssignation::query();
        if ($this->stages === 'Proyecto') {
            $title = array_merge($titleBase,$titleProject);
            $stageExport = $stageExport->with('cicsa_feasibility', 'cicsa_installation', 'cicsa_materials')->get();
        } elseif ($this->stages === 'Administracion') {
            $title = array_merge($titleBase,$titleAdministration);
            $stageExport = $stageExport->with('cicsa_purchase_order', 'cicsa_purchase_order_validation', 'cicsa_service_order')->get();
        } elseif ($this->stages === 'Cobranza') {
            $title = array_merge($titleBase,$titleOCNumber,$titleCobranza);
            $stageExport = $stageExport->with('cicsa_charge_area.cicsa_purchase_order')->get();
        } else {
            $title =  array_merge($titleBase,$titleProject, $titleAdministration, $titleCobranza);
            $stageExport = $stageExport->with('cicsa_feasibility', 'cicsa_installation', 'cicsa_materials', 'cicsa_purchase_order', 'cicsa_purchase_order_validation', 'cicsa_service_order', 'cicsa_charge_area')->get();
        }
        return view('Export.CicsaProcessExport', [
            'stages' => $this->stages,
            'title' => $title,
            'cicsaProcess' => $stageExport
        ]);
    }
}
