<?php

namespace App\Models;

use App\Constants\PintConstants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralExpense extends Model
{
    use HasFactory;
    protected $fillable = [
        'zone',
        'expense_type',
        'location',
        'amount',
        'operation_number',
        'operation_date',
        'account_statement_id',
        'doc_date',
        'doc_number',
        'type_doc',
        'type',
    ];

    protected $casts = [
        'amount' => 'double',
    ];

    protected $appends = [
        'validation',
        'bill'
    ];


    public function additional_cost()
    {
        return $this->hasOne(AdditionalCost::class, "general_expense_id");
    }
    public function static_cost()
    {
        return $this->hasOne(StaticCost::class, "general_expense_id");
    }
    public function pext_project_expense()
    {
        return $this->hasOne(PextProjectExpense::class, "general_expense_id");
    }
    public function payroll_detail_expense()
    {
        return $this->hasOne(PayrollDetailExpense::class, "general_expense_id");
    }
    public function huawei_monthly_expense()
    {
        return $this->hasOne(HuaweiMonthlyExpense::class, "general_expense_id");
    }


    public function getValidationAttribute()
    {
        $relations = [
            $this->additional_cost,
            $this->static_cost,
            $this->pext_project_expense,
            $this->payroll_detail_expense,
            $this->huawei_monthly_expense,
        ];
        $countNonNullRelations = collect($relations)->filter()->count();
        return $countNonNullRelations === 1;
    }

    public function getBillAttribute()
    {
        if (!$this->expense_type === PintConstants::FACTURA) {
            return ["serie" => null, "doc" => null];
        }
        if (!$this->validateDocNumber($this->doc_number)) {
            return ["serie" => null, "doc" => null];
        }
        return $this->separateDocNumber($this->doc_number);
    }


    private function validateDocNumber($input)
    {
        $pattern = '/^[a-zA-Z0-9]{4}-\d{1,10}$/';
        return preg_match($pattern, $input) === 1;
    }

    private function separateDocNumber($input)
    {
        $partes = explode('-', $input);
        $izquierda = $partes[0];
        $derecha = $partes[1];
        if (strlen($derecha) < 5) {
            $derecha = str_pad($derecha, 5, '0', STR_PAD_LEFT);
        } elseif (strlen($derecha) > 5) {
            $derecha = substr($derecha, -5);
        }
        return [
            'serie' => $izquierda,
            'doc' => $derecha,
        ];
    }

}
