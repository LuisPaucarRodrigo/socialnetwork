<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskEmployee extends Model
{
    use HasFactory;
    public function project_employee()
    {
        return $this->belongsTo(ProjectEmployee::class);
    }

    // En el modelo ProjectEmployee.php
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
