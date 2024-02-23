<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;
    protected $table = 'deposits';
    protected $fillable = [
        'operation_date',
        'operation_code',
        'operation_description',
        'denomination',
        'observation',
        'comission',
        'total_type',
        'total',
        'accounting_account_id',
    ] ;

    public function accounting_account () {
        return $this->belongsTo(AccountingAccount::class,'accounting_account_id');
    }
}
