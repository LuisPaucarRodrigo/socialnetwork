<?php

namespace App\Http\Requests\Cicsa;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateAssigantionRequest extends FormRequest
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
            'assignation_date' => 'required|date',
            'project_name' => 'required|string',
            'customer' => 'required|string',
            'project_code' => 'required|string',
            'cpe' => 'nullable|string',
            'project_deadline' => 'string|date|after:assignation_date',
            'user_name' => 'required|string',
            'user_id' => 'required|numeric',
        ];
    }
}
