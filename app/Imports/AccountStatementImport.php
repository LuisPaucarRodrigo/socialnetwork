<?php

namespace App\Imports;

use App\Models\AccountStatement;
use App\Models\AdditionalCost;
use App\Models\PextProjectExpense;
use App\Models\StaticCost;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;

class AccountStatementImport implements ToModel
{
    protected $skipHeader = true;
    protected $rowNumber = 1;
    protected $lastValidOperationDate = null;

    public function model(array $row)
    {
        if ($this->skipHeader) {
            $this->skipHeader = false;
            return null;
        }

        DB::beginTransaction();

        try {
            if (empty($row[0]) || empty($row[4])) {
                throw new \Exception("Error en la fila {$this->rowNumber}: Fecha o Cargo/Abono están vacías");
            }

            // Obtener y validar el valor de la columna 0 (fecha)
            $dateValue = $row[0];
            if (!preg_match('/^\d{2}-\d{2}$/', $dateValue)) {
                throw new \Exception("Error en la fila {$this->rowNumber}: Formato de fecha inválido en la columna Fecha. Debe ser 'DD-MM'.");
            }
            list($day, $month) = explode('-', $dateValue);
            $operationDate = \Carbon\Carbon::createFromDate(date('Y'), $month, $day);

            // Verificar si el valor de la columna 2 no está vacío y si el valor ya existe en la base de datos
            $operationNumber = $row[2];
            if (!empty($operationNumber) && AccountStatement::where('operation_number', $operationNumber)->exists()) {
                throw new \Exception("Error en la fila {$this->rowNumber}: El valor de la columna 2 (operation_number) ya existe.");
            }

            // Manejar la columna 4 para asignar 'charge' y 'payment'
            $amount = $this->parseAmount($row[4]);

            $accountStatement = AccountStatement::create([
                'operation_date' => $operationDate,
                'operation_number' => $operationNumber,
                'description' => $row[1],
                'charge' => $amount['charge'],
                'payment' => $amount['payment'],
            ]);

            //relations with add stat costs pext expenses
            $this->searchAndUpdateCosts($accountStatement->operation_date->toDateString(), $accountStatement->operation_number, $accountStatement->id);

            $this->rowNumber++; 
            $this->lastValidOperationDate = $operationDate;

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack(); 
            throw $e; 
        }
    }

    private function parseAmount($amountString)
    {
        $isNegative = substr($amountString, -1) === '-';
        $amountString = rtrim($amountString, '-');
        $amountString = str_replace(',', '', $amountString);
        $amount = floatval($amountString);
        return [
            'charge' => $isNegative ? $amount : null,
            'payment' => $isNegative ? null : $amount,
        ];
    }


    private function searchAndUpdateCosts($od, $on, $accountStatementId)
    {
        if ($od && $on) {
            AdditionalCost::where('operation_date', $od)
                ->where('operation_number', $on)
                ->update(['account_statement_id' => $accountStatementId]);
    
            StaticCost::where('operation_date', $od)
                ->where('operation_number', $on)
                ->update(['account_statement_id' => $accountStatementId]);
    
            PextProjectExpense::where('operation_date', $od)
                ->where('operation_number', $on)
                ->update(['account_statement_id' => $accountStatementId]);
        }
    }

}
