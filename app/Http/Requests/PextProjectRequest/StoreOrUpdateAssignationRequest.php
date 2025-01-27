<?php

namespace App\Http\Requests\PextProjectRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateAssignationRequest extends FormRequest
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
            'assignation_date' => 'nullable|date',
            'project_name' => 'required|string',
            'customer' => 'nullable|string',
            'project_code' => 'nullable|string',
            'cpe' => 'nullable|string',
            'zone' => 'required|string',
            'zone2' => 'nullable|string',
            'manager' => 'required|string',
            'user_name' => 'required|string',
            'user_id' => 'required|numeric',
            'project_id' => 'nullable|numeric',
            'pre_project_id' => 'nullable|numeric',
            'cost_line_id' => 'sometimes|required|exists:cost_centers,id',
            'cost_center_id' => 'sometimes|required|numeric'
        ];
    }
}
