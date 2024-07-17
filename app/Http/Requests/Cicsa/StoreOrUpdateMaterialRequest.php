<?php

namespace App\Http\Requests\Cicsa;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateMaterialRequest extends FormRequest
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
            'pick_date' => 'nullable|date',
            'guide_number' => 'nullable|string',
            'cicsa_material_items' => 'nullable|array',
            'user_name' => 'required|string',
            'user_id' => 'required|numeric',
            'cicsa_assignation_id' => 'sometimes|required|numeric'
        ];
    }
}
