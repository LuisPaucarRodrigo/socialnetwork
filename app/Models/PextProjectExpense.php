<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PextProjectExpense extends Model
{
    use HasFactory;
    protected $fillable = [
        'expense_type',
        'ruc',
        'type_doc',
        'zone',
        'operation_number',
        'operation_date',
        'doc_number',
        'doc_date',
        'description',
        'amount',
        'pext_project_id',
        'provider_id',
        'photo',
        'is_accepted',
        'state',
        'igv',
        'user_id',
        'cicsa_assignation_id',
        'account_statement_id'
    ];

    public function pext_project(){
        return $this->belongsTo(PextProject::class,'pext_project_id');
    }

    public function cicsa_assignation() {
        return $this->belongsTo(CicsaAssignation::class,'cicsa_assignation_id');
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }
    
    public function getRealAmountAttribute() {
        return $this->amount/(1+$this->igv/100);
    }

    protected static function booted()
    {
        static::updating(function ($expense) {
            if ($expense->isDirty('photo')) {
                $oldImage = $expense->getOriginal('photo');
                if ($oldImage) {
                    $filePath = public_path('documents/expensesPext/' . $oldImage);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
        });

        static::deleting(function ($expense) {
            if ($expense->photo) {
                $profile = public_path('documents/expensesPext/' . $expense->photo);
                if (file_exists($profile)) {
                    unlink($profile);
                }
            }
        });
    }
}
