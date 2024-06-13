<?php

namespace App\Http\Controllers\Huawei;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\HuaweiProject;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class FileDataController extends Controller
{
    public function render($project)
    {
        $huaweiData = HuaweiProject::where('project_id', $project)->paginate(10);
        return Inertia::render('Huawei/FileData',[
            'huaweiData' => $huaweiData,
            'project_id' => $project
        ]);
    }

    public function uploadExcel(Request $request)
    {
        $excelData = $request->input('statusData');
        // Definir el rango de filas y columnas que queremos extraer
        $startColumn = 'B';  // Columna de inicio
        $endColumn = 'N';    // Columna de fin
        $startRow = 6;       // Fila de inicio

        $selectedData = [];

        // Recorrer las filas especificadas y guardar los datos en $selectedData
        foreach ($excelData as $index => $row) {
            if ($index + 1 >= $startRow) {
                $selectedRow = array_slice($row, $this->columnIndex($startColumn) - 1, $this->columnIndex($endColumn) - $this->columnIndex($startColumn) + 1);

                // Rellenar con valores nulos si el tamaño del array es menor al número de columnas esperado
                $selectedRow = $this->fillNullValues($selectedRow, $this->columnIndex($endColumn) - $this->columnIndex($startColumn) + 1);

                // Comprobar si la fila está completamente vacía
                if (empty(array_filter($selectedRow))) {
                    break;  // Detener el bucle si la fila está vacía
                }

                $selectedData[] = $selectedRow;
            }
        }

        // Iniciar una transacción de base de datos
        DB::beginTransaction();

        try {
            foreach ($selectedData as $row) {
                // Crear un registro HuaweiProject utilizando el método create
                HuaweiProject::create([
                    'date' => $row[0],               // Segunda columna en $selectedRow
                    'codsap' => $row[1],             // Tercera columna en $selectedRow
                    'description' => $row[2],
                    'serie' => $row[3],
                    'period' => $row[4],
                    'hire' => $row[5],
                    'oc_pap' => $row[6],
                    'sites' => $row[7],
                    'general_status' => $row[8],
                    'status' => $row[9],
                    'monetary_value' => $row[10],
                    'observation' => $row[11] ?? null  // Asegurar que siempre haya 12 columnas en $row
                ]);
            }

            // Commit de la transacción si todo ha ido bien
            DB::commit();

            return response()->json(['message' => 'Datos insertados correctamente'], 200);
        } catch (\Exception $e) {
            // Rollback de la transacción en caso de error
            DB::rollBack();

            return response()->json(['message' => 'Error al insertar los datos: ' . $e->getMessage()], 500);
        }
    }

    // Rellenar con valores nulos si el tamaño del array es menor al número de columnas esperado
    private function fillNullValues($array, $expectedSize)
    {
        $currentSize = count($array);
        if ($currentSize < $expectedSize) {
            for ($i = $currentSize; $i < $expectedSize; $i++) {
                $array[] = null;
            }
        }
        return $array;
    }

    private function columnIndex($column)
    {
        $column = strtoupper($column);
        $length = strlen($column);
        $index = 0;

        for ($i = 0; $i < $length; $i++) {
            $index = $index * 26 + ord($column[$i]) - ord('A') + 1;
        }

        return $index;
    }
}

