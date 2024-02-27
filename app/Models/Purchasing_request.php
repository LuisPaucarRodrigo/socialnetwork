<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;


class Purchasing_request extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 
        'product_description', 
        'due_date', 
        'project_id',
        'is_accepted',
    ];

    protected $appends = ['state'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }


    public function purchase_quotes()    {
        return $this->hasMany(Purchase_quote::class);
    }

    
    public function getStateAttribute () {
        if ($this->is_accepted === 0) return 'Rechazada';
        $quotes = $this->purchase_quotes()->get();
        if ($quotes->isEmpty()) return 'Pendiente';
        foreach ($quotes as $item) {
            if ($item->state === null){ return 'En progreso';}
        }
        return 'Completada';
    }



}

