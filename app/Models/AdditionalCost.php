<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Constants\PintConstants;
use App\Constants\ProjectConstants;

class AdditionalCost extends Model
{
    use HasFactory;
    protected $table = 'additional_costs';
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
        'project_id',
        'provider_id',
        'photo',
        'is_accepted',
        'igv',
        'user_id',
        'general_expense_id',
        'account_statement_id',

    ];

    protected $casts = [
        'amount' => 'double',
    ];

    protected $appends = [
        'real_amount',
        'real_state'
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

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getRealAmountAttribute()
    {
        return $this->amount / (1 + $this->igv / 100);
    }

    public function getRealStateAttribute()
    {
        if ($this->is_accepted === 0) {
            return PintConstants::RECHAZADO;
        }
        if ($this->is_accepted && $this->general_expense()->first()?->account_statement_id) {
            return PintConstants::ACEPTADO_VALIDADO;
        }
        if ($this->is_accepted) {
            return PintConstants::ACEPTADO;
        }
        return PintConstants::PENDIENTE;
    }




    //EVENTS MODEL
    protected static function booted()
    {
        static::creating(function ($item) {
            $as = self::findAccountStatement($item);
            $generalExpense = GeneralExpense::create([
                'zone' => $item->zone,
                'expense_type' => $item->expense_type,
                'location' => $item?->project?->description ?? 'Sin descripción',
                'amount' => $item->amount,
                'operation_number' => $item->operation_number,
                'operation_date' => $item->operation_date,
                'doc_date' => $item->doc_date,
                'doc_number' => $item->doc_number,
                'type_doc' => $item->type_doc,
                'account_statement_id' => $as?->id,
                'type'=> ProjectConstants::EXP_TYPE_ADDITIONAL
            ]);

            $item->general_expense_id = $generalExpense->id;
        });

        static::updating(function ($item) {
            $generalExpense = $item->general_expense;
            $as = self::findAccountStatement($item);
            if ($generalExpense) {
                $generalExpense->update([
                    'zone' => $item->zone,
                    'expense_type' => $item->expense_type,
                    'location' => $item?->project?->description ?? 'Sin descripción',
                    'amount' => $item->amount,
                    'operation_number' => $item->operation_number,
                    'operation_date' => $item->operation_date,
                    'doc_date' => $item->doc_date,
                    'doc_number' => $item->doc_number,
                    'account_statement_id' => $as?->id,
                    'type_doc' => $item->type_doc,
                    'type'=> ProjectConstants::EXP_TYPE_ADDITIONAL
                ]);
            }
        });

        static::deleting(function ($item) {
            $generalExpense = $item->general_expense;
            if ($generalExpense) {
                $generalExpense->delete();
            }
        });
    }

   
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
