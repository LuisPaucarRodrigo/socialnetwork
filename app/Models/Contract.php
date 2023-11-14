<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;
    protected $fillable=['pension_system','basic_salary','hire_date','employee_id'];
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
