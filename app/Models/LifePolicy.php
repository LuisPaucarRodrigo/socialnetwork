<?php

namespace App\Models;

use Carbon\Carbon;
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

    protected $appends = [
        'amount_unique_people'
    ];

    //CALCULATED
    public function getAmountUniquePeopleAttribute()
    {
        $employeeCount = $this->employee()->count();
        if ($employeeCount == 0) {
            return 0;
        }

        $amount = $this->amount;
        $startDate = Carbon::parse($this->start_date);
        $endDate = Carbon::parse($this->end_date);

        $daysDifference = $startDate->diffInDays($endDate);

        if ($daysDifference == 0) {
            return 0;
        }

        return ($amount / 11) / $daysDifference;
    }

    //RELATIONS
    public function employee()
    {
        return $this->hasMany(Employee::class);
    }
}
