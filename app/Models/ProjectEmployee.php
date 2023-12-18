<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectEmployee extends Model
{
    use HasFactory;
    protected $table = 'project_employee';

    public function tasks() {
        return $this->belongsToMany(Tasks::class, 'task_employees');
    }
    public function employee_information() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
