<?php

namespace App\Http\Requests\FormationRequest;

use Illuminate\Foundation\Http\FormRequest;

class FormationProgramRequest extends FormRequest
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
            'name' => 'required|string',
            'month_year' => 'required|date',
            'type' => 'required|string|in:taller,curso,seminario,otros',
            'description' => 'required|string',
        ];
    }
}