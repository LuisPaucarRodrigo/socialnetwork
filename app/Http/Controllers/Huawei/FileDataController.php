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
        $huaweiData = HuaweiProject::where('project_id', $project)->paginate(30);
        return Inertia::render('Huawei/FileData',[
            'huaweiData' => $huaweiData,
            'project_id' => $project
        ]);
    }

    public function filter($project, Request $request)
    {
        // Obtener los parámetros de filtro del request
        $filters = $request->all();

        // Construir la consulta base
        $query = HuaweiProject::where('project_id', $project);

        // Aplicar filtros condicionalmente
        if (!empty($filters['startDate'])) {
            $query->whereDate('date', '>=', $filters['startDate']);
        }

        if (!empty($filters['endDate'])) {
            $query->whereDate('date', '<=', $filters['endDate']);
        }

        if (!empty($filters['minValue'])) {
            $query->where('monetary_value', '>=', $filters['minValue']);
        }

        if (!empty($filters['maxValue'])) {
            $query->where('monetary_value', '<=', $filters['maxValue']);
        }

        if (!empty($filters['generalStatus'])) {
            $query->where('general_status', $filters['generalStatus']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['codsap'])) {
            $query->whereRaw('LOWER(codsap) LIKE ?', ['%' . strtolower($filters['codsap']) . '%']);
        }

        if (!empty($filters['serie'])) {
            $query->whereRaw('LOWER(serie) LIKE ?', ['%' . strtolower($filters['serie']) . '%']);
        }

        if (!empty($filters['period'])) {
            $query->whereRaw('LOWER(period) LIKE ?', ['%' . strtolower($filters['period']) . '%']);
        }

        if (!empty($filters['hire'])) {
            $query->whereRaw('LOWER(hire) LIKE ?', ['%' . strtolower($filters['hire']) . '%']);
        }

        if (!empty($filters['ocPap'])) {
            $query->whereRaw('LOWER(oc_pap) LIKE ?', ['%' . strtolower($filters['ocPap']) . '%']);
        }

        if (!empty($filters['sites'])) {
            $query->whereRaw('LOWER(sites) LIKE ?', ['%' . strtolower($filters['sites']) . '%']);
        }

        $filteredResults = $query->paginate(30);

        // Devolver los resultados usando Inertia
        return Inertia::render('Huawei/FileData', [
            'huaweiData' => $filteredResults,
            'project_id' => $project
        ]);
    }

    public function uploadExcel($project, Request $request)
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

        DB::beginTransaction();

        try {
            HuaweiProject::where('project_id', $project)->delete();
            foreach ($selectedData as $row) {

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
                    'observation' => $row[11] ?? null,
                    'project_id' => $project  // Asegurar que siempre haya 12 columnas en $row
                ]);
            }

            // Commit de la transacción si todo ha ido bien
            DB::commit();

        } catch (\Exception $e) {
            // Rollback de la transacción en caso de error
            DB::rollBack();

        }
    }

    public function updateRegister ($project, HuaweiProject $itemToEdit, Request $request)
    {
        $data = $request->validate([
            'date' => 'nullable',
            'codsap' => 'nullable',
            'description' => 'nullable',
            'serie' => 'nullable',
            'period' => 'nullable',
            'hire' => 'nullable',
            'oc_pap' => 'nullable',
            'sites' => 'nullable',
            'general_status' => 'nullable',
            'status' => 'nullable',
            'monetary_value' => 'nullable',
            'observation' => 'nullable',
        ]);

        $itemToEdit->update($data);
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

