<?php

namespace App\Http\Requests\HumanResource\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class StorePayrollDetailMonetaryDiscountRequest extends FormRequest
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
        return [
            'id'=> 'nullable',
            'payroll_detail_id' => 'required',
            'discount_param_id' => 'required',
            'amount' => 'required',
        ];
    }
}
