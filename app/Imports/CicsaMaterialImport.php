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
        foreach ($collection as $row) {
            $this->data->push([
                'name' => $row[0],
                'unit' => $row[1],
                'quantity' => $row[2],
            ]);
        }
    }

    public function getData()
    {
        return $this->data;
    }
}
