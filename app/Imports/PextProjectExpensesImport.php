<?php

namespace App\Imports;

use App\Constants\PextConstants;
use App\Models\PextProjectExpense;
use App\Models\Project;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PextProjectExpensesImport implements ToCollection, WithStartRow
{
    /**
     * @param Collection $collection
     */

    private $project_id;
    private $fixedOrAdditional;
    private $startRow = 2;

    public function __construct($project_id, $fixedOrAdditional)
    {
        $this->project_id = $project_id;
        $this->fixedOrAdditional = $fixedOrAdditional;
    }

    public function startRow(): int
    {
        return $this->startRow;
    }

    private function verifyExpenseType($value, $rowIndex)
    {
        $normalizedValue = trim($value);
        $allowed = $this->fixedOrAdditional === "true" ? PextConstants::getExpenseTypeFixed() : PextConstants::getExpenseType();
        if (!in_array($normalizedValue, $allowed)) {
            throw new \Exception("Fila {$rowIndex}: 'Tipo de Gasto' inválido ({$normalizedValue})");
        }
        return $normalizedValue;
    }

    private function verifyZone($value, $rowIndex)
    {
        $normalizedValue = trim($value);
        $allowed = PextConstants::getZone();
        if (!in_array($normalizedValue, $allowed)) {
            throw new \Exception("Fila {$rowIndex}: 'Zona' está vacío o es invalido ({$normalizedValue})");
        }
        return $normalizedValue;
    }

    private function verifyTypeDoc($value, $rowIndex)
    {
        $normalizedValue = trim($value);
        $allowed = PextConstants::getDocumentsType();
        if (!in_array($normalizedValue, $allowed)) {
            throw new \Exception("Fila {$rowIndex}: Tipo de Documento está vacío o es invalido ({$normalizedValue})");
        }
        return $normalizedValue;
    }

    private function verifyIgv($value)
    {
        $normalizedValue = trim($value);
        $allowed = PextConstants::getZoneSinIGV();
        if (in_array($normalizedValue, $allowed)) {
            // throw new \Exception("Fila {$rowIndex}: Error en el IGV");
            return 0;
        }
        return 18;
    }

    private function verifyDate($value)
    {
        return Date::excelToDateTimeObject($value)->format('Y-m-d');
    }

    private function verifyOperationNumber($value)
    {
        $value = trim((string) $value);
        return str_pad($value, 6, '0', STR_PAD_LEFT);
    }

    public function collection(Collection $collection)
    {
        $project = Project::find($this->project_id);
        foreach ($collection as $index => $row) {
            $rowIndex = $index + $this->startRow;
            try {
                $item = [
                    'fixedOrAdditional' => $this->fixedOrAdditional === "true" ? 1 : 0,
                    'expense_type' => $this->verifyExpenseType($row[7], $rowIndex),
                    'ruc' => $row[4] ?? "Sin Ruc",
                    'type_doc' => $this->verifyTypeDoc($row[2], $rowIndex),
                    'zone' => $this->verifyZone($row[0], $rowIndex),
                    'operation_number' => $this->verifyOperationNumber($row[9]),
                    'operation_date' => $this->verifyDate($row[8]),
                    'doc_number' => $row[3],
                    'doc_date' => $this->verifyDate($row[1]),
                    'description' => $project->description . ' ' . $row[6],
                    'amount' => $row[5] ?? 0,
                    'provider_id' => null,
                    'photo' => null,
                    'is_accepted' => 1,
                    'igv' => $this->verifyIgv($row[0]),
                    'user_id' => null,
                    'project_id' => $project->id,
                    'account_statement_id' => null,
                    'general_expense_id' => null,
                ];
                PextProjectExpense::create($item);
            } catch (\Exception $e) {
                throw new \Exception("Error al procesar fila {$rowIndex}: " . $e->getMessage());
            }
        }
    }
}
