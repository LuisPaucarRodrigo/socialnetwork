<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id',
        'task',
        'percentage',
        'start_date',
        'end_date',
        'status',
    ];
    public static $rules = [
        'project_id' => 'required|exists:projects,id',
        'task' => 'required|string',
        'percentage' => 'required|integer|min:0|max:100',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'status' => 'required|in:pendiente,proceso,completado,detenido',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
    public function taskComment(){
        return $this->hasMany(TaskComment::class);
    }
    public function project_employee() {
        return $this->belongsToMany(ProjectEmployee::class, 'task_employees');
    }
}
