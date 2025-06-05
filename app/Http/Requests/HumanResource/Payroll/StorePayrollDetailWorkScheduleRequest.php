<?php

namespace App\Http\Requests\HumanResource\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class StorePayrollDetailWorkScheduleRequest extends FormRequest
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
            'id' => 'nullable',
            "payroll_detail_id" => "required",
            "subsidized_days" => "nullable|array",
            "non_subsidized_days" => "nullable|array",
            "regular_hours_0" => "required|integer|min:0",
            "regular_hours_1" => "required|integer|min:0|max:59",
            "overtime_hours_0" => "required|integer|min:0",
            "overtime_hours_1" => "required|integer|min:0|max:59",
        ];
    }
}
