<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'ruc',
        'business_name',
        'address'
    ];

    public function customer_contacts()
    {
        return $this->hasMany(Customers_contact::class);
    }

}
