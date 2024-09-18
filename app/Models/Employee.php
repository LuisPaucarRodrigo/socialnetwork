<?php

namespace App\Models;

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
        'phone2',
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

    protected static function booted()
    {
        static::updating(function ($employee) {
            if ($employee->isDirty('cropped_image')) {
                $oldImage = $employee->getOriginal('cropped_image');
                if ($oldImage) {
                    $filePath = public_path('image/profile/' . $oldImage);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
        });

        static::deleting(function ($employee) {
            if ($employee->cropped_image) {
                $profile = public_path('image/profile/' . $employee->cropped_image);
                if (file_exists($profile)) {
                    unlink($profile);
                }
            }
            $educations = $employee->education;
            if ($educations->curriculum_vitae) {
                $education = public_path('documents/curriculum_vitae/' . $educations->curriculum_vitae);
                if (file_exists($education)) {
                    unlink($education);
                }
            }
        });
    }
}
