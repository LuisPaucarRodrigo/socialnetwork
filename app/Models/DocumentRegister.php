<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRegister extends Model
{
    use HasFactory;
    protected $fillable = [
        'subdivision_id',
        'document_id',
        'employee_id',
        'e_employee_id',
        'exp_date',
        'state',
        'observations',
    ];
    
}
