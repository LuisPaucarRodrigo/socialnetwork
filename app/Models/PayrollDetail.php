<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayrollDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_id',
        'employee_id',
        'state',
        'pension_id',
        'basic_salary',
        'amount_travel_expenses',
        'life_ley',
        'discount',
        'discount_remuneration',
        'discount_sctr',
        'hire_date',
        'fired_date',
        'days_taken',
    ];

    protected $appends = [
        'employee_name',
        // 'total_income',
        // 'total_pension_base',
        // 'truncated_vacations',
        // 'snp',
        // 'snp_onp',
        // 'commission',
        // 'commission_on_ra',
        // 'seg',
        // 'insurance_premium',
        // 'mandatory_contribution',
        // 'mandatory_contribution_amount',
        // 'total_discount',
        // 'payment_until_today',
        // 'net_pay',
        // 'healths',
        // 'sctr_p',
        // 'sctr_s',
        // 'total_contribution',
        // 'new_totals',
    ];

    public function getEmployeeNameAttribute()
    {
        return $this->employee->name . ' ' . $this->employee->lastname;
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

    public function getSalaryAttribute()
    {
        return $this->basic_salary / 2;
    }

    public function getTruncatedVacationsAttribute()
    {
        return $this->fired_date !== null && $this->state === 'Inactive'
            ? ($this->salary / 12 * $this->truncated_month +
                ($this->salary / (30 * 12) * $this->truncated_days)) -
            (($this->basic_salary / 30) * $this->days_taken)
            : 0;
    }

    public function getPaymentUntilTodayAttribute()
    {
        $dayOfMonth = Carbon::now()->day;
        $dailySalary = $this->basic_salary / 30;
        return round($dailySalary * $dayOfMonth, 2);
    }

    public function getTotalIncomeAttribute()
    {
        $truncatedVacations = $this->truncated_vacations;
        return $this->payment_until_today - $this->discount + $truncatedVacations;
    }

    public function getTotalPensionBaseAttribute()
    {
        $truncatedVacations = $this->truncated_vacations;
        return $this->payment_until_today - $this->discount + $truncatedVacations;
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

    public function getEmployeesSctrAttribute()
    {
        return $this->where('discount_sctr', 1)->count();
    }

    public function getSctrPAttribute()
    {
        if ($this->discount_sctr) {
            $data = $this->payroll;
            return ($data->sctr_p / 3) / $this->employees_sctr;
        } else {
            return 0;
        }
    }

    public function getSctrSAttribute()
    {
        if ($this->discount_sctr) {
            $data = $this->payroll;
            return ($data->sctr_s / 3) / $this->employees_sctr;
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
        return $this->belongsTo(Payroll::class, 'payroll_id');
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

    public function payroll_detail_expense(): HasMany
    {
        return $this->hasMany(PayrollDetailExpense::class);
    }


    //////////////new

    public function getModDaysAttribute() {
        return $this->payroll->days;
    }
    public function payroll_detail_work_schedule() {
        return $this->hasOne(PayrollDetailWorkSchedule::class);
    }
    public function payroll_detail_monetary_income() {
        return $this->hasOne(PayrollDetailMonetaryIncome::class);
    }
    public function payroll_detail_monetary_discounts() {
        return $this->hasOne(PayrollDetailMonetaryDiscount::class);
    }
    public function payroll_detail_tax_and_contributions() {
        return $this->hasOne(PayrollDetailTaxAndContribution::class);
    }


    public function getNewTotalsAttribute () {
        $income_accrued_total = $this->payroll_detail_monetary_income()->sum('accrued_amount');
        $income_paid_total = $this->payroll_detail_monetary_income()->sum('paid_amount');
        $discount_total = $this->payroll_detail_monetary_discounts()->sum('amount');
        $employee_tac_total = $this->payroll_detail_tax_and_contributions()
            ->whereHas('tax_and_contribution_param', function($query){$query->where('type', 'employee');})
            ->sum('amount');
        $employer_tac_total = $this->payroll_detail_tax_and_contributions()
            ->whereHas('tax_and_contribution_param', function($query){$query->where('type', 'employer');})
            ->sum('amount');
        $net_pay=$income_paid_total-$discount_total-$employee_tac_total;
        return compact(
            'income_accrued_total',
            'income_paid_total',
            'discount_total',
            'employee_tac_total',
            'net_pay',
            'employer_tac_total'
        );
    }

    public function getMonetaryIncomesByIdsAttribute () {
        return $this->payroll_detail_monetary_income()->get()->keyBy('income_param_id')->toArray();
    }
    public function getMonetaryDiscountsByIdsAttribute () {
        return $this->payroll_detail_monetary_discounts()->get()->keyBy('discount_param_id')->toArray();
    }
    public function getTaxContributionEmployeeByIdsAttribute () {
        return $this->payroll_detail_tax_and_contributions()
            ->where('type', 'employee')
            ->get()->keyBy('t_a_c_param_id')->toArray();
    }
    public function getTaxContributionEmployerByIdsAttribute () {
        return $this->payroll_detail_tax_and_contributions()
            ->where('type', 'employer')
            ->get()->keyBy('t_a_c_param_id')->toArray();
    }
}
