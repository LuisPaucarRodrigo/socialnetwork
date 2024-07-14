<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CicsaChargeArea extends Model
{
    use HasFactory;

    protected $table = 'cicsa_charge_areas';

    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'payment_date',
        'deposit_date',
        'amount',
        'user_name',
        'user_id',
        'cicsa_assignation_id'
    ];

    protected $appends = [
        'credit',
        'days_late',
        'state'
    ];

    public function user ()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cicsa_assignation ()
    {
        return $this->belongsTo(CicsaAssignation::class, 'cicsa_assignation_id');
    }

    public function getCreditAttribute()
    {
        $paymentDate = new \DateTime($this->payment_date);
        $invoiceDate = new \DateTime($this->invoice_date);
            
        $interval = $paymentDate->diff($invoiceDate);
    
        return $interval->days;
    }

    public function getDaysLateAttribute()
    {
        $paymentDate = Carbon::parse($this->payment_date);
        $currentDate = Carbon::now();
    
        // Calcula la diferencia en días
        $daysLate = $paymentDate->diffInDays($currentDate, false);
    
        // Si la diferencia es negativa, retorna 0
        return $daysLate > 0 ? $daysLate : 0;
    }

    public function getStateAttribute ()
    {
        if (!$this->deposit_date && Carbon::now() < $this->payment_date){
            return 'A tiempo';
        }
        if ($this->deposit_date){
            return 'Pagado';
        }

        if (!$this->deposit_date && Carbon::now() > $this->payment_date){
            return 'Con deuda';
        }
    }
}
