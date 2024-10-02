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
        return [
            'subdivision_id'=> 'required',
            'document_id'=> 'nullable',
            'employee_id'=> 'nullable',
            'e_employee_id'=> 'nullable',
            'exp_date'=> 'nullable',
            'state'=> 'required',
            'observations'=> 'nullable',
        ];
    }
}
