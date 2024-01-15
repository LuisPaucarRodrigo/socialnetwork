<?php

namespace App\Imports\Inventory;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\NetworkEquipment;

class NetworkEquipmentImport implements  ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2; // Iniciar desde la tercera fila (puedes ajustarlo según tus necesidades).
    }
    public function model(array $row)
    {
        return new NetworkEquipment([
            'name' => $row[0],
            'model' => $row[1],
            'serie_number' => $row[2],
            'state' => $row[3],
            'adquisition_date' => $row[4],
            'supplier' => $row[5],
            'price' => $row[6],
        ]);
    }

}
