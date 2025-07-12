<?php

namespace App\Exports\Project\Expenses;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PextExpensesStructureExport implements FromArray, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return [
            'Zona',
            'Fecha Documento (dd/mm/yyyy)',
            'Tipo Documento',
            'N° Documento',
            'RUC',
            'Monto',
            'Descripción',
            'Tipo Gasto',
            'Fecha Operación (dd/mm/yyyy)',
            'N° Operación',
        ];
    }

    public function array(): array
    {
        return [];
    }
}
