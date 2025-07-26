<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ContractorEmployee extends Model
{
    use HasFactory;
    protected $connection = 'mysql_secondary';
    protected $table = 'employees';

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
        'l_policy',
        'sctr_exp_date',
        'policy_exp_date',
        'user_id',
    ];

    // protected $appends = [
    //     'sctr_about_to_expire',
    //     'policy_about_to_expire',
    //     'documents_about_to_expire',
    // ];

    //RELATIONS

    public function getTypeAttribute()
    {
        return 'employees';
    }

    public function user()
    {
        return $this->belongsTo(ContractorUser::class, 'user_id');
    }

    public function contract()
    {
        return $this->hasOne(ContractorContract::class);
    }
    public function education()
    {
        return $this->hasOne(ContractorEducation::class);
    }
    public function address()
    {
        return $this->hasOne(ContractorAddress::class);
    }
    public function emergency()
    {
        return $this->hasMany(ContractorEmergency::class);
    }
    public function family()
    {
        return $this->hasMany(ContractorFamily::class);
    }
    public function health()
    {
        return $this->hasOne(ContractorHealth::class);
    }
    public function vacation()
    {
        return $this->hasMany(ContractorVacation::class);
    }
    public function formation_programs()
    {
        return $this->belongsToMany(ContractorFormationProgram::class, 'employee_formation_program', 'employee_id', 'formation_program_id');
    }

    public function assignated_programs()
    {
        return $this->hasMany(ContractorEmployeeFormationProgram::class, 'employee_id');
    }

    public function document_registers()
    {
        return $this->hasMany(ContractorDocumentRegister::class, 'employee_id');
    }
    public function documents()
    {
        return $this->hasMany(ContractorDocument::class, 'employee_id');
    }

    public function salaryPerDay($days)
    {
        return $this->contract()->first()->basic_salary / $days;
    }

    public function getSctrAboutToExpireAttribute()
    {
        if (
            $this->contract()->first()?->discount_sctr
            && $this->sctr_exp_date
        ) {
            $actual = Carbon::now()->addDays(7);
            $exp_date = Carbon::parse($this->sctr_exp_date);
            return $actual >= $exp_date;
        }
        return null;
    }

    public function getPolicyAboutToExpireAttribute()
    {
        if (
            $this->l_policy && $this->policy_exp_date
        ) {
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
    public function getNoDocumentsAttribute()
    {
        $missing = ContractorSubdivision::where('section_id', '<=', 10)
            ->whereNotIn('id', $this->document_registers()->pluck('subdivision_id'))
            ->exists();
        return $missing;
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
