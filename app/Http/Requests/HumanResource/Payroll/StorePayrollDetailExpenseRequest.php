<?php

namespace App\Http\Requests\HumanResource\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class StorePayrollDetailExpenseRequest extends FormRequest
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
            "id" => 'nullable|numeric',
            "payroll_detail_id" => 'required|numeric',
            "general_expense_id" => 'nullable|numeric',
            "employee_name" => 'required|string',
            "photo_status" => "required|string|max:100",
            "photo" => 'nullable',
    
            "general_expense" => 'required|array',
            "general_expense.id" => 'nullable',
            "general_expense.zone" =>  'required|string|max:100',
            "general_expense.expense_type" =>  'required|string|max:100',
            "general_expense.location" =>  'required|string|max:150',
            "general_expense.operation_number" =>  'nullable|numeric|digits:6',
            "general_expense.operation_date" =>  'nullable|date',
            "general_expense.doc_date" =>  'nullable|date',
            "general_expense.doc_number" =>  'nullable|string|max:20',
            "general_expense.type_doc" =>  'required|string|max:50',
            "general_expense.amount" =>  'required|numeric|min:1',
        ];
    }
}
