<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LifePolicy extends Model
{
    use HasFactory;
    protected $fillable = [
        'start_date',
        'end_date',
        'amount'
    ];

    //CALCULATED

    //RELATIONS

    public function employee()
    {
        return $this->hasMany(Employee::class);
    }
}
