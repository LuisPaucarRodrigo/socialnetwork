<?php

namespace App\Http\Requests\GangExpenseRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGangExpenseRequest extends FormRequest
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
            'type_cdp' => 'required|string',
            'gang' => 'required|string',
            'person' => 'required|string',
            'date' => 'required|date',
            'number' => 'nullable|numeric|digits:9',
            'series' => 'required|string',
            'ruc' => 'nullable|numeric|digits:11',
            'price' => 'required|numeric',
            'description_expenses' => 'required|string',
            'type_expenses' => 'required|string',
            'percentaje' => 'nullable|numeric',
        ];
    }
}
