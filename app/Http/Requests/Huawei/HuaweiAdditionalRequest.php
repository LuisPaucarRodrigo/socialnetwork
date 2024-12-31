<?php

namespace App\Http\Requests\Huawei;

use Illuminate\Foundation\Http\FormRequest;

class HuaweiAdditionalRequest extends FormRequest
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
            'expense_type' => 'required',
            'employee' => 'required',
            'expense_date' => 'required|date',
            'cdp_type' => 'nullable',
            'doc_number' => 'nullable',
            'op_number' => 'nullable',
            'ruc' => 'nullable|digits:11',
            'description' => 'required',
            'amount' => 'required',
            'image1' => 'required',
            'image2' => 'nullable',
            'image3' => 'nullable',
            'refund_status' => 'required',
            'ec_expense_date' => 'nullable|date',
            'ec_op_number' => 'nullable',
            'ec_amount' => 'nullable',
        ];
    }
}
