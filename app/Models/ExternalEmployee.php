<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalEmployee extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'lastname',
        'cropped_image',
        'gender',
        'address',
        'birthdate',
        'dni',
        'email',
        'email_company',
        'phone1',
        'salary',
        'sctr',
        'curriculum_vitae'
    ];
}
