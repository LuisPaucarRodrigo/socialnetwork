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
        'curriculum_vitae',
        'l_policy',
        'sctr_exp_date',
        'policy_exp_date',
    ];

    public function document_registers () {
        return $this->hasMany(DocumentRegister::class, 'e_employee_id');
    }


    

}
