<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacation extends Model
{
    use HasFactory;
    protected $table = 'vacation';
    protected $fillable = [
        'employee_id',
        'type',
        'start_date',
        'end_date',
        'permissions',
        'start_date_accepted',
        'end_date_accepted',
        'reason',
        'status'
        ];
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
