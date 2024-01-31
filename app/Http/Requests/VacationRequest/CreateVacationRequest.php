<?php

namespace App\Http\Requests\VacationRequest;

use Illuminate\Foundation\Http\FormRequest;

class CreateVacationRequest extends FormRequest
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
            'employee_id' => 'required|numeric',
            'type' => 'required|in:Vacaciones,Permisos',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'start_permissions' => 'nullable|date_format:Y-m-d\TH:i',
            'end_permissions' => 'nullable|date_format:Y-m-d\TH:i|after:start_permissions',
            'doc_permission' => 'nullable|mimes:pdf|max:2048',
            'reason' => 'required|string',
        ];
    }
}
