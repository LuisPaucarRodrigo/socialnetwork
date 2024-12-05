<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PextProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'description',
        'project_id'
    ];

    public function pext_project_expenses()
    {
        return $this->hasMany(PextProjectExpense::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
