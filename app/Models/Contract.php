<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;
    protected $fillable=['basic_salary','discount_remuneration','state','days_taken','hire_date','fired_date','employee_id','pension_id'];

    protected $appends = [
        'total_income',
        'total_pension_base',

        'truncated_month',
        'truncated_days',
        'half_salary',
        'salary_day',

        'truncated_vacations',
        'snp',
        'snp_onp',
        'commission',
        'commission_on_ra',
        'seg',
        'insurance_premium',
        'mandatory_contribution',
        'mandatory_contribution_amount',
        'total_discount',
        'net_pay',
        'healths',
        'life_ley',
        'total_contribution'
    ];

    public function pension()
    {
        return $this->belongsTo(Pension::class, 'pension_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }




    public function getTruncatedMonthAttribute()
    {
        return Carbon::parse($this->fired_date)->diffInMonths(Carbon::parse($this->hire_date));
    }

    public function getTruncatedDaysAttribute()
    {
        $truncatedMonth = $this->truncated_month;
        return Carbon::parse($this->fired_date)->diffInDays(Carbon::parse($this->hire_date)) - ($truncatedMonth * 30) + 1;
    }

    public function getHalfSalaryAttribute()
    {
        return $this->basic_salary / 2;
    }

    public function getSalaryDayAttribute()
    {
        return $this->basic_salary / 2;
    }

    public function getTruncatedVacationsAttribute()
    {
        return $this->fired_date !== null && $this->state === 'Inactive'
            ? ($this->half_salary / 12 * $this->truncated_month  +
                ($this->half_salary / (30 * 12) * $this->truncated_days)) -
            (($this->basic_salary / 30) * $this->days_taken)
            : 0;
    }

    public function getTotalIncomeAttribute()
    {
        $truncatedVacations = $this->truncated_vacations;
        return $this->basic_salary + $truncatedVacations;
    }

    public function getTotalPensionBaseAttribute()
    {
        $truncatedVacations = $this->truncated_vacations;
        return $this->basic_salary + $truncatedVacations;
    }

    public function getSnpAttribute()
    {
        return $this->pension->type == 'ONP' ? 100 * $this->pension->values : 0;
    }

    public function getSnpOnpAttribute()
    {
        return $this->pension->type == 'ONP' ? $this->total_income * $this->pension->values: 0;
    }

    public function getCommissionAttribute()
    {
        return $this->pension->type == 'ONP' ? 0 : ($this->discount_remuneration == 1 ? 100 * $this->pension->values : 0);
    }

    public function getCommissionOnRaAttribute()
    {
        return $this->pension->type == 'ONP' ? 0 : ($this->discount_remuneration == 1 ? $this->total_income * $this->pension->values : 0);
    }

    public function getSegAttribute()
    {
        return $this->pension->type == 'ONP' ? 0 : 100 * $this->pension->values_seg;
    }

    public function getInsurancePremiumAttribute()
    {
        return $this->pension->type == 'ONP' ? 0 : $this->total_income * $this->pension->values_seg;
    }

    public function getMandatoryContributionAttribute()
    {
        return $this->pension->type == 'ONP' ? 0 : 10;
    }

    public function getMandatoryContributionAmountAttribute()
    {
        return $this->pension->type == 'ONP' ? 0 : $this->total_income * 0.1;
    }

    public function getTotalDiscountAttribute()
    {
        return $this->snp_onp + $this->commission_on_ra + $this->insurance_premium + $this->mandatory_contribution_amount;
    }

    public function getNetPayAttribute()
    {
        return $this->total_income - $this->total_discount;
    }

    public function getHealthsAttribute()
    {
        return $this->total_income * 0.09;
    }

    public function getLifeLeyAttribute()
    {
        return 0;
    }

    public function getTotalContributionAttribute()
    {
        return $this->healths + $this->life_ley;
    }
}
