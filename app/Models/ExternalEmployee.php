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
        'cost_line_id',
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

    // protected $appends = [
    //     'sctr_about_to_expire',
    //     'policy_about_to_expire',
    // ];

    public function getTypeAttribute()
    {
        return 'external';
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

    public function getNoDocumentsAttribute()
    {
        $missing = Subdivision::where('section_id', '<=', 10)
            ->whereNotIn('id', $this->document_registers()->pluck('subdivision_id'))
            ->exists();
        return $missing;
    }

    public function cost_line()
    {
        return $this->belongsTo(CostLine::class, 'cost_line_id');
    }

    public function document_registers()
    {
        return $this->hasMany(DocumentRegister::class, 'e_employee_id');
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
            if ($employee->isDirty('curriculum_vitae')) {
                $oldImage = $employee->getOriginal('curriculum_vitae');
                if ($oldImage) {
                    $filePath = public_path('documents/curriculum_vitae/' . $oldImage);
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
            if ($employee->curriculum_vitae) {
                $employee = public_path('documents/curriculum_vitae/' . $employee->curriculum_vitae);
                if (file_exists($employee)) {
                    unlink($employee);
                }
            }
        });
    }
}
