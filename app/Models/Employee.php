<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'lastname',
        'cropped_image',
        'gender',
        'state_civil',
        'birthdate',
        'dni',
        'email',
        'email_company',
        'phone1',
        'phone2'
    ];

    protected $appends = [
        'total_income',
        'total_pension_base',
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

    public function contract()
    {
        return $this->hasOne(Contract::class);
    }
    public function education()
    {
        return $this->hasOne(Education::class);
    }
    public function address()
    {
        return $this->hasOne(Address::class);
    }
    public function emergency()
    {
        return $this->hasMany(Emergency::class);
    }
    public function family()
    {
        return $this->hasMany(Family::class);
    }
    public function health()
    {
        return $this->hasOne(Health::class);
    }
    public function vacation()
    {
        return $this->hasMany(Vacation::class);
    }
    public function formation_programs()
    {
        return $this->belongsToMany(FormationProgram::class, 'employee_formation_program', 'employee_id', 'formation_program_id');
    }
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_employee')->withPivot('charge');
    }
    public function project_employees()
    {
        return $this->hasOne(ProjectEmployee::class);
    }

    public function getSalaryPerDayAttribute()
    {
        return $this->contract()->first()->basic_salary / 30;
    }




    public function getTruncatedVacationsAttribute()
    {
        return number_format($this->calculateTruncatedVacations(), 2);
    }

    public function getTotalIncomeAttribute()
    {
        $truncatedVacations = $this->calculateTruncatedVacations();
        return $this->contract->basic_salary + $truncatedVacations;
    }

    public function getTotalPensionBaseAttribute()
    {
        $truncatedVacations = $this->calculateTruncatedVacations();
        return $this->contract->basic_salary + $truncatedVacations;
    }

    public function getSnpAttribute()
    {
        return $this->contract->pension->type == 'ONP' ? number_format(100 * $this->contract->pension->values, 2) : number_format(0, 2);
    }

    public function getSnpOnpAttribute()
    {
        return $this->contract->pension->type == 'ONP' ? number_format(($this->total_income * $this->contract->pension->values), 2) : number_format(0, 2);
    }

    public function getCommissionAttribute()
    {
        return $this->contract->pension->type == 'ONP' ? number_format(0, 2) : number_format(100 * $this->contract->pension->values, 2);
    }

    public function getCommissionOnRaAttribute()
    {
        return $this->contract->pension->type == 'ONP' ? number_format(0, 2) : $this->total_income * $this->contract->pension->values;
    }

    public function getSegAttribute()
    {
        return $this->contract->pension->type == 'ONP' ? number_format(0, 2) : number_format(100 * $this->contract->pension->values_seg, 2);
    }

    public function getInsurancePremiumAttribute()
    {
        return $this->contract->pension->type == 'ONP' ? number_format(0, 2) : number_format($this->total_income * $this->contract->pension->values_seg, 2);
    }

    public function getMandatoryContributionAttribute()
    {
        return $this->contract->pension->type == 'ONP' ? number_format(0, 2) : 10;
    }

    public function getMandatoryContributionAmountAttribute()
    {
        return $this->contract->pension->type == 'ONP' ? number_format(0, 2) : number_format($this->total_income * 0.1, 2);
    }

    public function getTotalDiscountAttribute()
    {
        return number_format(($this->snp_onp + $this->commission_on_ra + $this->insurance_premium + $this->mandatory_contribution_amount), 2);
    }

    public function getNetPayAttribute()
    {
        return number_format(($this->total_income - $this->total_discount), 2);
    }

    public function getHealthsAttribute()
    {
        return number_format(($this->total_income * 0.09), 2);
    }

    public function getLifeLeyAttribute()
    {
        return number_format(0, 2); // Ajusta este valor según tu lógica específica
    }

    public function getTotalContributionAttribute()
    {
        return number_format($this->healths + $this->life_ley, 2);
    }

    private function calculateTruncatedVacations()
    {
        if ($this->contract->fired_date !== null && $this->contract->state === 'Inactive') {
            $yearsWorked = Carbon::parse($this->contract->fired_date)->floatDiffInYears(Carbon::parse($this->contract->hire_date));
            return floor($yearsWorked * 15 * ($this->contract->basic_salary / 30));
        }
        return 0;
    }
}
