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
        return $this->created_at->addDays($this->purchase_quote->deliverable_time)->format('d/m/Y');
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
        $arrivalDate = $this->purchaseArrivalDate; // Obtener la fecha de llegada de la orden de compra
        $quotePayments = $this->purchase_quote->payment; // Obtener todos los pagos relacionados con la cotización

        foreach ($quotePayments as $payment) {
            if ($payment->created_at <= $arrivalDate && $payment->state != 0) {
                // Si encontramos algún pago con fecha anterior a la fecha de llegada y estado no completado (1), devolvemos false
                return false;
            }
        }

        // Si todos los pagos con fecha anterior a la llegada están completados (state = 1) o no hay ninguno, devolvemos true
        return true;
    }

}
