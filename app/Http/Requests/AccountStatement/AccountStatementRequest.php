<?php

namespace App\Http\Requests\AccountStatement;

use Illuminate\Foundation\Http\FormRequest;

class AccountStatementRequest extends FormRequest
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
            'operation_date' => 'required',
            'operation_number' => 'required',
            'description' => 'required',
            'charge' => 'required',
            'payment' => 'required',
            'acData' => 'nullable|array',
            'acData.*' => 'integer',
            'scData' => 'nullable|array',
            'scData.*' => 'integer',
        ];
    }
}
