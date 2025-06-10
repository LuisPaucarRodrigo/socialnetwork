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
            'employee_id' => 'required_without:e_employee_id|prohibited_with:e_employee_id',
            'e_employee_id' => 'required_without:employee_id|prohibited_with:employee_id',
            'exp_date' => 'nullable',
            'state' => 'required',
            'observations' => 'nullable',
        ];
        if ($this->input('state') === 'Completado') {
            $rules['document'] = 'required|file|mimes:png,pdf,jpeg,jpg';
        }
        return $rules;
    }

}
