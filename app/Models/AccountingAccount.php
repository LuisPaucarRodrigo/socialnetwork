<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountingAccount extends Model
{
    use HasFactory;
    protected $table = 'accounting_accounts';
    protected $fillable = [
        'code',
        'denomination',
    ];


    public function deposits () {
        return $this->hasMany(Deposit::class, 'accounting_account_id');
    }
}
