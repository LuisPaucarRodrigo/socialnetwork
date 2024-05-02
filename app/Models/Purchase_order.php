<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase_order extends Model
{
    use HasFactory;
    protected $fillable = [
        'state',
        'purchase_quote_id', 
        'serie_number',
        'facture_doc',
        'facture_date',
        'facture_number',
        'others',
        'remission_guide_doc',
        'remission_guide_date',
        'remission_guide_number',
    ];

    protected $appends = [
        'purchase_arrival_date',
        'code',
        'is_payments_completed'
    ];

    public function getPurchaseArrivalDateAttribute(){
        return $this->created_at->addDays($this->purchase_quote->deliverable_time+1)->format('Y-m-d');
    }    

    public function purchase_quote()
    {
        return $this->belongsTo(Purchase_quote::class, 'purchase_quote_id');
    }

    public function getCodeAttribute()
    {
        if ($this->exists) {
            return 'OC' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
        } else {
            return 'TMP' . now()->format('ymdHis');
        }
    }

    public function getIsPaymentsCompletedAttribute()
    {
        $arrivalDate = $this->purchaseArrivalDate;
        $quotePayments = $this->purchase_quote->payment;

        foreach ($quotePayments as $payment) {
            if ($payment->register_date <= $arrivalDate && !$payment->payment_doc) {
                return false; // Si algún pago está pendiente antes de la fecha de llegada, devolvemos false
            }
        }

        return true; // Si todos los pagos están completos o son posteriores a la fecha de llegada, devolvemos true
    }
}
