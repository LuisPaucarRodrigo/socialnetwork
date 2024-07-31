<?php

namespace App\Imports;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Provider;

class CostsImport implements ToModel
{
    protected $modelClass;
    protected $project_id;
    protected $skipHeader = true;

    public function __construct($modelClass, $project_id)
    {
        $this->modelClass = $modelClass;
        $this->project_id = $project_id;
    }

    public function model(array $row)
    {
        if (!class_exists($this->modelClass)) {
            throw new \Exception('El modelo no existe.');
        }
        if ($this->skipHeader) {
            $this->skipHeader = false;
            return null; // O no retornar nada para la cabecera
        }

        $model = app($this->modelClass);
        return new $model([
            'zone' => $row[2],
            'expense_type' => $row[3],  
            'type_doc' => $row[4],
            'ruc' => $row[5],
            'doc_number' => $row[7],
            'doc_date' => $row[8],
            'amount' => $row[9],
            'description' => $row[10],
            'provider_id' => null,
            'photo' => null,
            'project_id' => $this->project_id,  // Columna 3
        ]);
    }
}
