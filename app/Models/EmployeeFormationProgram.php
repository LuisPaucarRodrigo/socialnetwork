<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeFormationProgram extends Model
{
    use HasFactory;
    protected $table = 'employee_formation_program';

    public function formation_program() {
        return $this->belongsTo(FormationProgram::class, 'formation_program_id');
    }

}
