<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'payroll_id',
        'employee_id',
        'pension_id',

        'basic_salary',
        'amount_travel_expenses',
        'life_ley',
        'discount_remuneration',
        'discount_sctr',
        'hire_date',
        'fired_date',
        'days_taken'
    ];

    protected $appends = [
        'total_income',
        'total_pension_base',
        'truncated_month',
        'truncated_days',
        'salary',

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
        'sctr_p',
        'sctr_s',
        'total_contribution'
    ];

    public function getTruncatedMonthAttribute()
    {
        return Carbon::parse($this->fired_date)->diffInMonths(Carbon::parse($this->hire_date));
    }

    public function getTruncatedDaysAttribute()
    {
        $truncatedMonth = $this->truncated_month;
        return Carbon::parse($this->fired_date)->diffInDays(Carbon::parse($this->hire_date)) - ($truncatedMonth * 30) + 1;
    }

    public function getSalaryAttribute()
    {
        return $this->basic_salary / 2;
    }

    public function getTruncatedVacationsAttribute()
    {
        return $this->fired_date !== null && $this->state === 'Inactive'
            ? ($this->salary / 12 * $this->truncated_month  +
                ($this->salary / (30 * 12) * $this->truncated_days)) -
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
        // return 0;
    }

    public function getSnpOnpAttribute()
    {
        return $this->pension->type == 'ONP' ? $this->total_income * $this->pension->values : 0;
        // return 0;
    }

    public function getCommissionAttribute()
    {
        return $this->pension->type == 'ONP' ? 0 : ($this->discount_remuneration == 1 ? 100 * $this->pension->values : 0);
        // return 0;
    }

    public function getCommissionOnRaAttribute()
    {
        return $this->pension->type == 'ONP' ? 0 : ($this->discount_remuneration == 1 ? $this->total_income * $this->pension->values : 0);
        // return 0;
    }

    public function getSegAttribute()
    {
        return $this->pension->type == 'ONP' ? 0 : 100 * $this->pension->values_seg;
        // return 0;
    }

    public function getInsurancePremiumAttribute()
    {
        return $this->pension->type == 'ONP' ? 0 : $this->total_income * $this->pension->values_seg;
        // return 0;
    }

    public function getMandatoryContributionAttribute()
    {
        return $this->pension->type == 'ONP' ? 0 : 10;
        // return 0;
    }

    public function getMandatoryContributionAmountAttribute()
    {
        return $this->pension->type == 'ONP' ? 0 : $this->total_income * 0.1;
        // return 0;
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

    public function getSctrPAttribute()
    {
        if ($this->discount_sctr) {
            $data = json_decode(File::get(config_path('custom.json')), true);
            return ($data['sctr_p'] / $data['number_people']) / 90;
        } else {
            return 0;
        }
    }

    public function getSctrSAttribute()
    {
        if ($this->discount_sctr) {
            $data = json_decode(File::get(config_path('custom.json')), true);
            return ($data['sctr_s'] / $data['number_people']) / 90;
        } else {
            return 0;
        }
    }

    public function getTotalContributionAttribute()
    {
        return $this->healths + $this->life_ley + $this->sctr_p + $this->sctr_s;
    }

    // Relación con Payroll
    public function payroll()
    {
        return $this->belongsTo(Payroll::class,'payroll_id');
    }

    // Relación con Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Relación con Pension
    public function pension()
    {
        return $this->belongsTo(Pension::class);
    }

    
}
