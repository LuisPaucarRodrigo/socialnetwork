<?php

namespace App\Http\Requests\Huawei;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\HuaweiMonthlyProject as ModelsHuaweiMonthlyProject;
use Carbon\Carbon;

class HuaweiMonthlyExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'expense_type' => 'required',
            'zone' => 'nullable',
            'employee' => 'required',
            'expense_date' => 'required|date',
            'cdp_type' => 'required',
            'doc_number' => 'nullable',
            'op_number' => 'nullable',
            'ruc' => 'nullable|digits:11',
            'description' => 'required',
            'amount' => 'required',
            'image' => 'nullable',
            'ec_expense_date' => 'nullable|date',
            'ec_op_number' => 'nullable',
            'ec_amount' => 'nullable',
            'huawei_project_id' => 'nullable|exists:huawei_projects,id',
        ];
    }
}
