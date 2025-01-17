<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectQuote extends Model
{
    use HasFactory;
    protected $fillable = [
        'delivery_place',
        'delivery_time',
        'observations',
        'fee',
        'project_id',
        'user_id'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function project_quote_valuations()
    {
        return $this->hasMany(ProjectQuoteValuation::class);
    }
}
