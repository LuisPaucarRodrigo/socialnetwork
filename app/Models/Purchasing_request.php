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
        'due_date', 
        'project_id',
        'preproject_id',
        'is_accepted',
    ];

    protected $appends = ['state', 'code'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function preproject()
    {
        return $this->belongsTo(Preproject::class, 'preproject_id');
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

    public function purchasing_request_product()
    {
        return $this->hasMany(Purchasing_requests_product::class);
    }

    public function products()
    {
        return $this->belongsToMany(Purchase_product::class, 'purchasing_requests_products', 'purchasing_request_id', 'purchase_product_id')->withPivot('id','quantity');
    }

    public function getCodeAttribute() {
        return 'SC' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }

}

