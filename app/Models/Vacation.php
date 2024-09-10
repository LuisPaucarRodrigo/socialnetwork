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
        'start_permissions',
        'end_permissions',
        'review_date',
        'doc_permission',
        'reason',
        'status',
        'coment'
        ];
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
