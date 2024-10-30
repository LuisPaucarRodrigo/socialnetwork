<?php

namespace App\Http\Requests\Huawei;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\Huawei\HuaweiMonthlyProject;
use App\Models\HuaweiMonthlyProject as ModelsHuaweiMonthlyProject;
use Carbon\Carbon;

class HuaweiMonthlyExpenseRequest extends FormRequest
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
            'zone' => 'required',
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
            'huawei_monthly_project_id' => 'required|exists:huawei_monthly_projects,id'
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $projectId = $this->input('huawei_monthly_project_id');

            // Buscar el proyecto y obtener la fecha en formato 'Y-m'
            $project = ModelsHuaweiMonthlyProject::find($projectId);

            if ($project) {
                $projectDate = Carbon::createFromFormat('Y-m', $project->date);
                $startOfMonth = $projectDate->startOfMonth();
                $endOfMonth = $projectDate->endOfMonth();

                // Validar que 'expense_date' esté dentro del rango permitido
                $expenseDate = Carbon::parse($this->input('expense_date'));

                if ($expenseDate->lt($startOfMonth) || $expenseDate->gt($endOfMonth)) {
                    $validator->errors()->add('expense_date', 'La fecha de gasto debe estar dentro del mes y año del proyecto.');
                }
            }
        });
    }
}
