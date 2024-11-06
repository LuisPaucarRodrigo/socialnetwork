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

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $projectId = $this->input('huawei_monthly_project_id');
            $expenseDateInput = $this->input('expense_date');

            // Verificar que el ID del proyecto esté presente y exista
            $project = ModelsHuaweiMonthlyProject::find($projectId);

            if ($project && $expenseDateInput) {
                // Obtener solo el mes y el año del proyecto
                $projectMonth = Carbon::createFromFormat('Y-m', $project->date)->format('Y-m');

                // Obtener el mes y el año de la fecha de gasto
                $expenseMonth = Carbon::parse($expenseDateInput)->format('Y-m');

                // Comparar ambos valores
                if ($expenseMonth !== $projectMonth) {
                    $validator->errors()->add('expense_date', 'La fecha de gasto debe estar dentro del mismo mes y año del proyecto: ' . $project->date);
                }
            } else {
                $validator->errors()->add('huawei_monthly_project_id', 'El proyecto no existe o no se ha seleccionado.');
            }
        });
    }
}
