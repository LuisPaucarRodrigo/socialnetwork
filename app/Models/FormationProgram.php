<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormationProgram extends Model
{
    use HasFactory;
    protected $table = "formation_programs";
    protected $fillable = [
        'name',
        'description',
    ];

    public function trainings() 
    {
        return $this->belongsToMany(Training::class,'formation_program_training' );
    }


    public function employees() 
    {
        return $this->belongsToMany(Employee::class,'employee_formation_program', 'formation_program_id', 'employee_id');
    }

}
