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
        'start_date',
        'end_date',
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
