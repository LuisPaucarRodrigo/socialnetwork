<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preproject_contact extends Model
{
    use HasFactory;
    protected $table = 'preprojects_contacts';
    protected $fillable = [
        'preproject_id',
        'customer_contact_id'
    ] ;
}
