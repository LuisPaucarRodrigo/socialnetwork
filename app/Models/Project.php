<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $table = 'projects';
    protected $fillable = [
        'name',
        'code',
        'start_date',
        'end_date',
        'priority',
        'description'
    ];

    public function employees()
    {
        return $this->belongsToMany(Employee::class,'project_employee')->withPivot('charge', 'id');
    }
    public function tasks(){
        return $this->hasMany(Tasks::class);
    }
}
