<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorEducation extends Model
{
    use HasFactory;
    protected $connection = 'mysql_secondary';
    protected $table = 'education';

    protected $fillable = [
        'education_level',
        'education_status',
        'specialization',
        'curriculum_vitae',
        'employee_id'
    ];

    public function employee()
    {
        return $this->belongsTo(ContractorEmployee::class, 'employee_id');
    }

    protected static function booted()
    {
        static::updating(function ($education) {
            if ($education->isDirty('curriculum_vitae')) {
                $oldImage = $education->getOriginal('curriculum_vitae');
                if ($oldImage) {
                    $filePath = public_path('documents/curriculum_vitae/' . $oldImage);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
        });
    }
}
