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
use App\Models\ToolsGtd;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CicsaMaterialImport implements ToCollection, WithStartRow
{
    /**
     * @param Collection $collection
     */

    public $data;

    public function startRow(): int
    {
        return 2;
    }

    public function __construct()
    {
        $this->data = collect();
    }

    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            $this->data->push([
                'code_ax' => $row[0],
                'name' => $row[1],
                'unit' => $row[2],
                'type' => $row[3],
                'quantity' => $row[4],
            ]);
        }
    }

    public function getData()
    {
        return $this->data;
    }
}
