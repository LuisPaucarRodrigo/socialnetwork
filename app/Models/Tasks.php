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

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function taskComment()
    {
        return $this->hasMany(TaskComments::class);
    }
    
    public function project_employee()
    {
        return $this->belongsToMany(ProjectEmployee::class, 'task_employees');
    }
}
