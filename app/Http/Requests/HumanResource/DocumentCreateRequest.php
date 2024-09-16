<?php

namespace App\Http\Requests\HumanResource;

use Illuminate\Foundation\Http\FormRequest;

class DocumentCreateRequest extends FormRequest
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
            'document' => 'required',
            'subdivision_id' => 'required|numeric',
            'employeeType' => 'required',
            'has_exp_date' => 'required',
        ];
        
        
        if ($this->input('employeeType')){
            $rules['employee_id'] = 'required';
        } else {
            $rules['e_employee_id'] = 'required';
        }
        if ($this->input('has_exp_date')){
            $rules['exp_date'] = 'required';
        }
        return $rules;
    }
}
