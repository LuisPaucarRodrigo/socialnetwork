<?php

namespace App\Imports;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;

class CostsImport implements ToModel
{
    protected $modelClass;
    protected $project_id;

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
        $model = app($this->modelClass);

        return new $model([
            'codigo' => $row[0], // Columna 1
            'name' => $row[1],   // Columna 2
            'email' => $row[2],  // Columna 3
            'project_id' => $this->project_id,  // Columna 3
        ]);
    }
}
