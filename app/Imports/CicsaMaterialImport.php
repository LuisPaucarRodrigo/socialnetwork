<?php

namespace App\Imports;

use App\Models\CicsaAssignation;
use App\Models\CicsaChargeArea;
use App\Models\CicsaFeasibility;
use App\Models\CicsaInstallation;
use App\Models\CicsaPurchaseOrder;
use App\Models\CicsaPurchaseOrderValidation;
use App\Models\CicsaSection;
use App\Models\CicsaServiceOrder;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CicsaMaterialImport implements ToCollection, WithStartRow, WithLimit
{
    /**
     * @param Collection $collection
     */

    public $data;

    public function limit(): int
    {
        return 139; // Ajusta el número de filas que deseas importar
    }

    public function startRow(): int
    {
        return 2; // Iniciar desde la tercera fila (puedes ajustarlo según tus necesidades).
    }

    public function __construct()
    {
        $this->data = collect();
    }

    public function collection(Collection $collection)
    {
        // foreach ($collection as $row) {
        //     $this->data->push([
        //         'name' => $row[0],
        //         'unit' => $row[1],
        //         'quantity' => $row[2],
        //     ]);
        // }
        // $date3 = !empty($row[10]) ? Carbon::parse($row[10])->addDays(3)->format('Y-m-d') : null;
        // $date4 = !empty($row[10]) ? Carbon::parse($row[10])->addDays(4)->format('Y-m-d') : null;
        // $date6 = !empty($row[10]) ? Carbon::parse($row[10])->addDays(6)->format('Y-m-d') : null;

        foreach ($collection as $row) {
            $data = [
                'project_name' => !empty($row[2]) ? strval($row[2]) : "Sin nombre",
                'assignation_date' => !empty($row[3]) ? strval($row[3]) : null,
                'customer' => !empty($row[4]) ? strval($row[4]) : null,
                'project_code' => !empty($row[5]) ? strval($row[5]) : null,
                'cpe' => !empty($row[6]) ? strval($row[6]) : null,
                'project_deadline' => !empty($row[7]) ? strval($row[7]) : null,
                'user_name' => "Valery Joanna Montalvan Huillca",
                'user_id' => 9,
            ];
            $dataId = CicsaAssignation::create($data);

            // $this->data->push([
            //     'pext_date' => strval($row[10]),
            //     'pint_date' => strval($row[10]),
            //     'conformity' => strval($row[16]),
            //     'report' => strval($row[17]),
            //     'shipping_report_date' => strval($row[10]),
            //     'user_name' => "Valery Joanna Montalvan Huillca",
            //     'user_id' => 9,
            //     'cicsa_assignation_id' => 1,
            // ]);

            $dataFeasibility = [
                'feasibility_date' => strval($row[10]),
                'report' => strval($row[11]),
                'user_name'=> "Valery Joanna Montalvan Huillca",
                'user_id' => 9,
                'cicsa_assignation_id' => $dataId->id,
            ];
            CicsaFeasibility::create($dataFeasibility);

            $dataInstallation = [
                'pext_date' => strval($row[10]),
                'pint_date' => strval($row[10]),
                'conformity' => strval($row[16]),
                'report' => strval($row[17]),
                'shipping_report_date' => strval($row[10]),
                'user_name' => "Valery Joanna Montalvan Huillca",
                'user_id' => 9,
                'cicsa_assignation_id' => $dataId->id,
            ];
            CicsaInstallation::create($dataInstallation);

            $dataOC = [
                'oc_date' => !empty($row[23]) ? strval($row[23]) : null,
                'oc_number' => !empty($row[24]) ? strval($row[24]) : null,
                'master_format' => strval($row[25]),
                'item3456' => strval($row[26]),
                'budget' => strval($row[27]),
                'user_name' => "Valery Joanna Montalvan Huillca",
                'user_id' => 9,
                'cicsa_assignation_id' => $dataId->id,
            ];
            CicsaPurchaseOrder::create($dataOC);

            $daraPurchaseValidation = [
                'validation_date' => null,
                'materials_control' => !empty($row[31]) ? "Completado" : "Pendiente",
                'supervisor' => !empty($row[32]) ? "Completado" : "Pendiente",
                'warehouse' => !empty($row[33]) ? "Completado" : "Pendiente",
                'boss' => !empty($row[34]) ? "Completado" : "Pendiente",
                'liquidator' => !empty($row[35]) ? "Completado" : "Pendiente",
                'superintendent' => !empty($row[36]) ? "Completado" : "Pendiente",
                'user_name' => "Valery Joanna Montalvan Huillca",
                'user_id' => 9,
                'cicsa_assignation_id' => $dataId->id,
            ];

            CicsaPurchaseOrderValidation::create($daraPurchaseValidation);
            
            $dataService = [
                'service_order_date' => null,
                'service_order' => !empty($row[40]) ? strval($row[40]) : null,
                'estimate_sheet' => !empty($row[41]) ? strval($row[41]) : "Pendiente",
                'purchase_order' => !empty($row[42]) ? strval($row[42]) : "Pendiente",
                'pdf_invoice' => !empty($row[43]) ? strval($row[43]) : "Pendiente",
                'zip_invoice' => !empty($row[44]) ? strval($row[44]) : "Pendiente",
                'user_name' => "Valery Joanna Montalvan Huillca", 
                'user_id' => 9,
                'cicsa_assignation_id' => $dataId->id,
            ];
            CicsaServiceOrder::create($dataService);

            $dataCharge = [
                'invoice_number' => !empty($row[47]) ? strval($row[47]) : null,
                'invoice_date' => !empty($row[48]) ? strval($row[48]) : null,
                'credit_to' => null,
                'deposit_date' => null,
                'amount' => !empty($row[54]) ? strval($row[54]) : null,
                'user_name'=> "Valery Joanna Montalvan Huillca", 
                'user_id' => 9,
                'cicsa_assignation_id' => $dataId->id,
            ];
            CicsaChargeArea::create($dataCharge);
        }
    }

    public function getData()
    {
        return $this->data;
    }
}
