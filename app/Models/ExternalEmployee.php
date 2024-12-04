<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ExternalEmployee extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'lastname',
        'expense_line',
        'cropped_image',
        'gender',
        'address',
        'birthdate',
        'dni',
        'email',
        'email_company',
        'phone1',
        'salary',
        'sctr',
        'curriculum_vitae',
        'l_policy',
        'sctr_exp_date',
        'policy_exp_date',
    ];

    protected $appends = [
        'sctr_about_to_expire',
        'policy_about_to_expire',
    ];

    public function document_registers()
    {
        return $this->hasMany(DocumentRegister::class, 'e_employee_id');
    }

    public function getSctrAboutToExpireAttribute()
    {
        if ($this->sctr && $this->sctr_exp_date) {
            $actual = Carbon::now()->addDays(7);
            $exp_date = Carbon::parse($this->sctr_exp_date);
            return $actual >= $exp_date;
        }
        return null;
    }

    public function getPolicyAboutToExpireAttribute()
    {
        if ($this->l_policy && $this->policy_exp_date) {
            $actual = Carbon::now()->addDays(7);
            $exp_date = Carbon::parse($this->policy_exp_date);
            return $actual >= $exp_date;
        }
        return null;
    }

    public function getDocumentsAboutToExpireAttribute()
    {
        $total = $this->document_registers()->get()->filter(function ($item) {
            return $item->display === true;
        })
            ->sum('display');
        if ($this->sctr_about_to_expire) {
            $total += 1;
        }
        if ($this->policy_about_to_expire) {
            $total += 1;
        }
        return $total;
    }
}
