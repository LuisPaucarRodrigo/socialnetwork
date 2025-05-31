<?php

namespace App\Http\Requests\HumanResource\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class StorePayrollDetailMonetaryIncomeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'id'=> 'nullable',
            'payroll_detail_id' => 'required',
            'income_param_id' => 'required',
        ];
        if($this->input('accrued_amount')) $rules['accrued_amount'] = 'required';
        if($this->input('paid_amount')) $rules['paid_amount'] = 'required';
        return $rules;
    }
}
