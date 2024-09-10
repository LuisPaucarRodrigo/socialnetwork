<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_name',
        'contact_name',
        'address',
        'phone1',
        'phone2',
        'email',
        'category',
        'segment',
        'zone',
        'ruc',
    ];
}
