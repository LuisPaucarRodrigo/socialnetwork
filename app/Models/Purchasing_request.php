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

    protected $appends = [
        'state', 
        'code', 
        'state_quote',
        'products_state'
    ];

    public function getStateAttribute()
    {
        if ($this->is_accepted === 0) return 'Rechazada';
        $quotes = $this->purchase_quotes()->get();
        if ($quotes->isEmpty()) return 'Pendiente';
        foreach ($quotes as $item) {
            if ($item->state === null) {
                return 'En progreso';
            }
        }
        if (!$this->getProductsStateAttribute()) return 'En progreso';
        return 'Completada';
    }

    public function getProductsStateAttribute() {
        foreach($this->purchasing_request_product()->get() as $item){
            if (!$item->completed_state){
                return false;
            }
        }
        return true;
    }


    public function getCodeAttribute()
    {
        return 'SC' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }

    public function getStateQuoteAttribute()
    {
        $allComplete = true;

        foreach ($this->purchase_quotes as $quote) {
            if (empty($quote->quote_deadline)) {
                $allComplete = false;
                break;
            }
        }

        return $allComplete;
    }

    // FUNCION
    public function checkQuotesProductsQuantity($objectToCheck)
    {
        $pr_products = $this->purchasing_request_product()->with('purchase_product')->get();
        foreach ($pr_products as $pr_product) {
            $id_product = $pr_product->purchase_product->id;
            $productToEvaluate = $objectToCheck[$id_product];
            if ($productToEvaluate["quantity"] > ($pr_product->quantity - $pr_product->actual_quotes_quantity))
                return false;
        }
        return true;
    }

    //RELATIONS
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function preproject()
    {
        return $this->belongsTo(Preproject::class, 'preproject_id');
    }

    public function products()
    {
        return $this->belongsToMany(Purchase_product::class, 'purchasing_requests_products', 'purchasing_request_id', 'purchase_product_id')->withPivot('id', 'quantity');
    }

    public function purchase_quotes()
    {
        return $this->hasMany(Purchase_quote::class);
    }

    public function purchasing_request_product()
    {
        return $this->hasMany(Purchasing_requests_product::class);
    }

    
}
