<?php

namespace App\Http\Requests\Cicsa;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateFeasibilitiesRequest extends FormRequest
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
            'feasibility_date' => 'nullable|date',
            'report' => 'required|string',
            'user_name' => 'required|string',
            'user_id' => 'required|numeric',
            'cicsa_feasibility_materials' => 'nullable|array'
        ];
    }
}
