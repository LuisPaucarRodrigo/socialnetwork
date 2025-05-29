<?php

namespace App\Http\Requests\Huawei;

use Illuminate\Foundation\Http\FormRequest;

class HuaweiMobileRequest extends FormRequest
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
        $expenseType = $this->input('expense_type') === 'EFECTIVO';

        return [
            'huawei_project_id' => 'nullable',
            'expense_type' => 'required|string',
            'cdp_type' => 'required|string',
            'doc_number' => [
                $expenseType ? 'nullable' : 'required',
                'string',
            ],
            'ruc' => [
                $expenseType ? 'nullable' : 'required',
                'string',
            ],
            'description' => 'required|string',
            'amount' => 'required|numeric',
            'image' => [
                $expenseType ? 'nullable' : 'required',
            ],
        ];
    }

}
