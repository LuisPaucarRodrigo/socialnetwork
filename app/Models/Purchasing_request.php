<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchasing_request extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'product_description', 'due_date', 'state', 'response', 'project_id'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function purchase_quotes()
    {
        return $this->hasOne(Purchase_quote::class);
    }
}

