<?php

namespace App\Http\Requests\CostsRequest;

use Illuminate\Foundation\Http\FormRequest;

class AdministrativeCostsRequest extends FormRequest
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
            'expense_type' => 'required|string',
            'ruc' => 'required|numeric|digits:11',
            'type_doc' => 'required',
            "operation_number" => 'nullable|min:6',
            "operation_date" => 'nullable|date',
            'doc_number' => 'nullable|string',
            'doc_date' => 'nullable|date',
            'amount' => [
                'required',
                'numeric',
              
            ],
            'zone' => 'required',
            'provider_id' => 'nullable',
            'description' => 'required|string',
            'photo' => 'nullable',
            'igv' => 'required',
            'month_project_id' => 'required'
        ];

        return $rules;
    }
}
