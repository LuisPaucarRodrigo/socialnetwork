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

    public function tasks()
    {
        return $this->belongsTo(Tasks::class, 'task_id');
    }
}
