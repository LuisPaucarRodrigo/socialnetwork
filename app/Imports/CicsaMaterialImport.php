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
    }

    public function __construct()
    {
        $this->data = collect();
    }

    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {

        }
    }

    public function getData()
    {
        return $this->data;
    }
}
