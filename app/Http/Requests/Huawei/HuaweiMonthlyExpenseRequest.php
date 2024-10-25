<?php

namespace App\Http\Requests\Huawei;

use Illuminate\Foundation\Http\FormRequest;

class HuaweiMonthlyExpenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'expense_type' => 'required',
            'zone' => 'required',
            'employee' => 'required',
            'expense_date' => 'required',
            'cdp_type' => 'nullable',
            'doc_number' => 'nullable',
            'op_number' => 'nullable',
            'ruc' => 'nullable',
            'description' => 'required',
            'amount' => 'required',
            'refund_status' => 'required',
            'ec_expense_date' => 'nullable',
            'ec_op_number' => 'nullable',
            'ec_amount' => 'nullable'
        ];
    }
}
