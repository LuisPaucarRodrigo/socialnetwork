<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetUpdate extends Model
{
    use HasFactory;
    protected $table = 'budget_updates';
    protected $fillable = [
        'new_budget',
        'project_id',
        'reason',
        'update_date',        
        'user_id',
        'approved_update_date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class,'project_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
