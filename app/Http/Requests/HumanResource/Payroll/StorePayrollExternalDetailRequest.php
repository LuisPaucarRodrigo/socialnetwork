<?php

namespace App\Http\Requests\HumanResource\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class StorePayrollExternalDetailRequest extends FormRequest
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
            "id" => "nullable",
            "payroll_id" => "required",
            "name" => "required",
            "lastname" => "required",
            "doc_type" => "required",
            "doc_number" => "required",
            "amount" => "required",
            "ret_tax" => "required",
        ];
    }
}
