<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase_quote extends Model
{
    use HasFactory;
    protected $fillable = [
        'quote_deadline', 
        'purchase_doc', 
        'response', 
        'state',
        'igv',
        'igv_percentage',
        'deliverable_time',
        'payment_type',
        'account_number',
        'purchasing_request_id',
        'provider_id',
        'currency',
        'change_value',
        'preproject_state'
    ];

    public $appends = [
        'total_amount', 'code','payments_completed'
    ];

    public function provider()    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

    public function purchasing_requests()
    {
        return $this->belongsTo(Purchasing_request::class, 'purchasing_request_id');
    }

    public function purchase_order() {
        return $this->hasOne(Purchase_order::class);
    }

    public function payment() {
        return $this->hasMany(Payment::class);
    }

    public function products () {
        return $this->belongsToMany(Purchase_product::class,'purchase_quotes_products')->withPivot('id','quantity','unitary_amount')->withTimestamps();
    }

    public function purchase_quote_products() {
        return $this->hasMany(Purchase_quotes_product::class,'purchase_quote_id');
    }

    public function getTotalAmountAttribute() {
        return $this->purchase_quote_products()->get()->sum(function($item){
            return $item->quantity * $item->unitary_amount;
        });
    }

    public function getCodeAttribute() {
        return 'CO' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }

    public function getPaymentsCompletedAttribute()
    {
        $quotePayments = $this->payment; // Obtenemos todos los pagos relacionados con la cotización
        $allCompleted = true; // Suponemos que todos los pagos están completados

        foreach ($quotePayments as $payment) {
            if (!$payment->state) {
                // Si al menos uno de los pagos no está completado, marcamos la bandera como falsa
                $allCompleted = false;
                break; // No es necesario seguir iterando
            }
        }

        return $allCompleted;
    }
}
