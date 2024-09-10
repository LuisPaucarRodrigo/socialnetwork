<?php

namespace App\Http\Requests\VacationRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVacationRequest extends FormRequest
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
            'type' => 'required|in:Vacaciones,Permisos',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'start_permissions' => 'nullable',
            'end_permissions' => 'nullable|after:start_permissions',
            'start_date_accepted' => 'nullable|date',
            'end_date_accepted' => 'nullable|date|after:start_date_accepted',
            'reason' => 'required|string',
        ];
    }
}
