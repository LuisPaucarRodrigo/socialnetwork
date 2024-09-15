<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'phone2',
        'l_policy',
        'sctr_exp_date',
        'policy_exp_date',
    ];

    protected $appends = [
        'sctr_about_to_expire'
    ];

    //RELATIONS

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

    public function assignated_programs()
    {
        return $this->hasMany(EmployeeFormationProgram::class, 'employee_id');
    }

    public function document_registers () {
        return $this->hasMany(DocumentRegister::class, 'employee_id');
    }



    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_employee')->withPivot('charge');
    }
    public function project_employees()
    {
        return $this->hasOne(ProjectEmployee::class);
    }

    public function salaryPerDay($days)
    {
        return $this->contract()->first()->basic_salary / $days;
    }

    public function getSctrAboutToExpireAttribute () {
        if ($this->contract()->first()?->discount_sctr
            && $this->sctr_exp_date
        ){
            $actual = Carbon::now()->addDays(7);
            $exp_date = Carbon::parse($this->sctr_exp_date);
            return $exp_date >= $actual;
        }  
        return null;
    }
}
