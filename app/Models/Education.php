<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;
    protected $fillable = ['education_level','education_status','specialization','employee_id'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
