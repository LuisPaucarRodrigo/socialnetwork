<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;


class Purchase_product extends Model
{
    use HasFactory;
    protected $table = "purchase_products";
    protected $fillable = [
        'name', 
        'unit',
        'description',
        'type',
        'type_product',
        'state'
    ];

    public $appends = [
        'code'
    ];

    public function purchasing_request_product()
    {
        return $this->hasMany(Purchasing_requests_product::class);
    }

    public function purchase_quote_product() 
    {
        return $this->hasMany(Purchase_quotes_product::class);
    }

    public function purchasing_request()
    {
        return $this->belongsToMany(Purchasing_request::class, 'purchasing_requests_products', 'purchase_product_id', 'purchasing_request_id')->withTimestamps();
    }
    public function purchase_quotes()
    {
        return $this->belongsToMany(Purchase_quote::class, 'purchase_quotes_products', 'purchase_product_id', 'purchase_quote_id')->withPivot('id','quantity','unitary_amount');
    }

    public function getCodeAttribute()
    {
        if ($this->exists) {
            if ($this->type == 'Producto') {
                return 'PR' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
            } elseif ($this->type == 'Servicio') {
                return 'SE' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
            } elseif ($this->type == 'Activo') {
                return 'AC' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
            } else {
                // Si el tipo no coincide con ninguno de los valores especificados, regresa null o un valor predeterminado
                return null; // O puedes devolver 'XX' u otro valor predeterminado si lo prefieres
            }
        } else {
            return 'TMP' . now()->format('ymdHis');
        }
    }
}

