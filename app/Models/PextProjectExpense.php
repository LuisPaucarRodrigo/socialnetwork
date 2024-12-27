<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PextProjectExpense extends Model
{
    use HasFactory;
    protected $fillable = [
        'fixedOrAdditional',
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
        // 'state',
        'igv',
        'user_id',
        'project_id',
        'account_statement_id',
        'general_expense_id',
    ];

    public function general_expense()
    {
        return $this->belongsTo(GeneralExpense::class, 'general_expense_id');
    }
    
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

    public function getRealAmountAttribute()
    {
        return $this->amount / (1 + $this->igv / 100);
    }

    // protected static function booted()
    // {
    //     static::creating(function ($item) {
    //         $as = self::findAccountStatement($item);
    //         $generalExpense = GeneralExpense::create([
    //             'zone' => $item->zone,
    //             'expense_type' => $item->expense_type,
    //             'location' => $item?->project?->description ?? 'Sin descripción',
    //             'amount' => $item->amount,
    //             'operation_number' => $item->operation_number,
    //             'operation_date' => $item->operation_date,
    //             'account_statement_id' => $as?->id,
    //         ]);
    //         $item->general_expense_id = $generalExpense->id;
    //     });


    //     static::updating(function ($expense) {
    //         if ($expense->isDirty('photo')) {
    //             $oldImage = $expense->getOriginal('photo');
    //             if ($oldImage) {
    //                 $filePath = public_path('documents/expensesPext/' . $oldImage);
    //                 if (file_exists($filePath)) {unlink($filePath);}
    //             }
    //         }
    //         $generalExpense = $expense->general_expense;
    //         $as = self::findAccountStatement($expense);
    //         if ($generalExpense) {
    //             $generalExpense->update([
    //                 'zone' => $expense->zone,
    //                 'expense_type' => $expense->expense_type,
    //                 'location' => $expense?->project?->description ?? 'Sin descripción',
    //                 'amount' => $expense->amount,
    //                 'operation_number' => $expense->operation_number,
    //                 'operation_date' => $expense->operation_date,
    //                 'account_statement_id' => $as?->id,
    //             ]);
    //         }
    //     });

    //     static::deleting(function ($expense) {
    //         if ($expense->photo) {
    //             $profile = public_path('documents/expensesPext/' . $expense->photo);
    //             if (file_exists($profile)) {
    //                 unlink($profile);
    //             }
    //         }
    //     });
    // }

    protected static function findAccountStatement($item)
    {
        if ($item->operation_number && $item->operation_date) {
            $on = substr($item->operation_number, -6);
            return AccountStatement::where('operation_date', $item->operation_date)
                ->where('operation_number', $on)->first();
        }
        return null;
    }
}
