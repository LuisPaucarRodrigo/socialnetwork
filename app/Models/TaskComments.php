<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskComments extends Model
{
    use HasFactory;
    protected $fillable = [
        'task_id',
        'comment',
    ];
    public static $rules = [
        'task_id' => 'required|exists:tasks,id',
        'comment' => 'required|string',
    ];
    public function tasks()
    {
        return $this->belongsTo(Tasks::class, 'task_id');
    }
}
