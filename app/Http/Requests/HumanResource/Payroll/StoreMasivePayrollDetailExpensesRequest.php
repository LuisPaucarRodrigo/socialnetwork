<?php

namespace App\Http\Requests\HumanResource\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class StoreMasivePayrollDetailExpensesRequest extends FormRequest
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
            'payroll_detail_expenses'=> 'required|array',
            'payroll_detail_expenses.*.payroll_detail_id' => 'required|numeric',
            'payroll_detail_expenses.*.employee_name' => 'required|string|max:255',
            'payroll_detail_expenses.*.zone' => 'required|string|max:255',
            'payroll_detail_expenses.*.expense_type' => 'required|string|max:255',
            'payroll_detail_expenses.*.type_doc' => 'required|string|max:255',
            'payroll_detail_expenses.*.location' => 'required|string|max:255',
            'payroll_detail_expenses.*.operation_number' => 'nullable|numeric|digits:6',
            'payroll_detail_expenses.*.operation_date' => 'nullable|date',
            'payroll_detail_expenses.*.doc_date' => 'nullable|date',
            'payroll_detail_expenses.*.doc_number' => 'nullable|string|max:10',
            'payroll_detail_expenses.*.amount' => 'required|numeric|min:1',
        ];
    }
}
