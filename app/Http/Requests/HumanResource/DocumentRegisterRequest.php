<?php

namespace App\Http\Requests\HumanResource;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRegisterRequest extends FormRequest
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
        $rules = [
            'subdivision_id' => 'required',
            'document_id' => 'nullable',
            'employee_id' => 'nullable',
            'e_employee_id' => 'nullable',
            'has_exp_date' => 'required',
            'state' => 'required',
            'observations' => 'nullable',
        ];
        $rules['exp_date'] = $this->input('has_exp_date') === '1' ? 'required' : 'nullable';
        if ($this->input('state') === 'Completado') {
            $rules['document'] = 'required|file|mimes:png,pdf,jpeg,jpg';
        }


        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $employeeId = $this->input('employee_id');
            $eEmployeeId = $this->input('e_employee_id');

            if (empty($employeeId) && empty($eEmployeeId)) {
                $validator->errors()->add('employee_id', 'Debes completar uno de los dos campos: employee_id o e_employee_id.');
            }

            if (!empty($employeeId) && !empty($eEmployeeId)) {
                $validator->errors()->add('employee_id', 'Solo uno de los campos employee_id o e_employee_id debe estar completo, no ambos.');
            }
        });
    }

}
