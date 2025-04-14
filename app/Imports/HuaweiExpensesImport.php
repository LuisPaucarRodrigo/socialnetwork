<?php

namespace App\Imports;

use App\Constants\HuaweiConstants;
use App\Models\HuaweiMonthlyExpense;
use App\Models\HuaweiProject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class HuaweiExpensesImport implements ToModel
{
    protected $skipHeader = true;
    protected $rowNumber = 1;
    private static array $data;
    private static bool $stopImport = false;

    public function __construct()
    {
        self::$data = [
            'employees' => HuaweiConstants::getEmployees(),
            'static_expense_types' => HuaweiConstants::getStaticExpenseTypes(),
            'variable_expense_types' => HuaweiConstants::getVariableExpenseTypes(),
            'cdp_types' => HuaweiConstants::getCDPTypes(),
        ];
    }

    public function model(array $row)
    {
        if (self::$stopImport) {
            return null;
        }

        if ($this->skipHeader) {
            $this->skipHeader = false;
            return null;
        }

        if (collect($row)->every(fn ($value) => trim((string)$value) === '')) {
            self::$stopImport = true;
            return null;
        }

        DB::beginTransaction();

        $rowError = $this->rowNumber + 1;

        try {
            if (empty($row[0]) || empty($row[2]) || empty($row[3]) || empty($row[7]) || empty($row[8]) || empty($row[9])) {
                throw new \Exception("Error en la fila {$rowError}: Llene los campos obligatorios.");
            }

            $cdpType = $this->validateText($row[3], "CdpType");
            $expenseType = $this->validateText($row[9], "ExpenseType");
            
            if (is_null($cdpType)) {
                throw new \Exception("Error en la fila {$rowError}: El CDP especificado no está admitido.");
            }
            if (is_null($expenseType)) {
                throw new \Exception("Error en la fila {$rowError}: El Tipo de Gasto especificado no está admitido.");
            }

            $project = $row[1] ? HuaweiProject::where('assigned_diu', $row[1])->first() : null;

            $date1 = $row[2];
            $date2 = $row[11];
            
            if ($date1 instanceof \DateTimeInterface) {
                $date1 = $date1->format('d-m-Y');
            } elseif (is_numeric($date1)) {
                $date1 = ExcelDate::excelToDateTimeObject($date1)->format('d-m-Y');
            } else {
                $date1 = trim((string) $date1);
            }
            
            if (!empty($date2)) {
                if ($date2 instanceof \DateTimeInterface) {
                    $date2 = $date2->format('d-m-Y');
                } elseif (is_numeric($date2)) {
                    $date2 = ExcelDate::excelToDateTimeObject($date2)->format('d-m-Y');
                } else {
                    $date2 = trim((string) $date2);
                }
            }

            if (!preg_match('/^\d{2}-\d{2}-\d{4}$/', $date1)) {
                Log::info($date1);
                throw new \Exception("Error en la fila {$rowError}: Formato de fecha inválido en la columna Fecha de gasto. Debe ser 'DD-MM-YYYY'.");
            }
            
            [$day1, $month1, $year1] = explode('-', $date1);
            if (!checkdate((int)$month1, (int)$day1, (int)$year1)) {
                Log::info($date1);
                throw new \Exception("Error en la fila {$rowError}: La fecha en la columna Fecha de gasto no es válida.");
            }
            
            $expenseDate = \Carbon\Carbon::createFromDate((int)$year1, (int)$month1, (int)$day1);
            
            // Validación solo si la fecha de operación NO es null o vacía
            if (!empty($date2)) {
                if (!preg_match('/^\d{2}-\d{2}-\d{4}$/', $date2)) {
                    Log::info($date2);
                    throw new \Exception("Error en la fila {$rowError}: Formato de fecha inválido en la columna Fecha de operación. Debe ser 'DD-MM-YYYY'.");
                }
            
                [$day2, $month2, $year2] = explode('-', $date2);
                if (!checkdate((int)$month2, (int)$day2, (int)$year2)) {
                    Log::info($date2);
                    throw new \Exception("Error en la fila {$rowError}: La fecha en la columna Fecha de operación no es válida.");
                }
            
                $opDate = \Carbon\Carbon::createFromDate((int)$year2, (int)$month2, (int)$day2);
            } else {
                $opDate = null;
            }
            

            $accountStatement = HuaweiMonthlyExpense::create([
                'employee' => $row[0],
                'project_id' => $project->id ?? null,
                'expense_date' => $expenseDate,
                'cdp_type' => $cdpType,
                'doc_number' => $row[4],
                'op_number' => $row[5],
                'ruc' => $row[6],
                'amount' => $this->sanitizeNumber($row[7]),
                'description' => $row[8],
                'expense_type' => $expenseType,
                'ec_expense_date' => $opDate,
                'ec_op_number' => $row[12],
                'ec_amount' => $this->sanitizeNumber($row[13]),
            ]);

            $this->rowNumber++;

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function sanitizeNumber($text)
    {
        // Remover todos los caracteres que no sean dígitos o puntos
        $sanitized = preg_replace('/[^0-9.]/', '', $text);

        // Si hay más de un punto, conservar solo el primero
        if (substr_count($sanitized, '.') > 1) {
            $parts = explode('.', $sanitized);
            $sanitized = array_shift($parts) . '.' . implode('', $parts);
        }

        // Eliminar ceros a la izquierda innecesarios
        $sanitized = ltrim($sanitized, '0');

        // Si después de limpiar queda vacío, devolver "0"
        return $sanitized === '' ? '0' : $sanitized;
    }


    private function validateText($input, $type)
    {
        if ($type == "ExpenseType") {
            $validValues = array_merge(self::$data['static_expense_types'], self::$data['variable_expense_types']);
        } elseif ($type == "CdpType") {
            $validValues = self::$data['cdp_types'];
        } else {
            return null;
        }
        return in_array($input, $validValues) ? $input : null;
    }

}
