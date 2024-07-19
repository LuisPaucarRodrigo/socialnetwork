<?php

namespace App\Imports;

use App\Models\CicsaAssignation;
use App\Models\CicsaPurchaseOrder;
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
        return 3; // Iniciar desde la tercera fila (puedes ajustarlo según tus necesidades).
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
        foreach ($collection as $row) {
            $data = [
                'project_name' => !empty($row[1]) ? strval($row[1]) : null,
                'assignation_date' => !empty($row[2]) ? strval($row[2]) : null,
                'customer' => !empty($row[3]) ? strval($row[3]) : null,
                'project_code' => !empty($row[4]) ? strval($row[4]) : null,
                'cpe' => !empty($row[5]) ? strval($row[5]) : null,
                'project_deadline' => !empty($row[2]) ? strval($row[2]) : null,
                'user_name' => !empty($row[7]) ? strval($row[7]) : null,
                'user_id' => 1
            ];
            $dataId = CicsaAssignation::create($data);

            $dataOC = [
                'oc_date' => !empty($row[2]) ? strval($row[2]) : null,
                'oc_number' => !empty($row[10]) ? strval($row[10]) : null,
                'master_format' => strval($row[11]),
                'item3456' => strval($row[12]),
                'budget' => strval($row[13]),
                'user_name' => !empty($row[7]) ? strval($row[7]) : null,
                'user_id' => 1,
                'cicsa_assignation_id' => $dataId->id,
            ];
            CicsaPurchaseOrder::create($dataOC);

        }
    }

    public function getData()
    {
        return $this->data;
    }
}
