<?php

namespace App\Http\Requests\AccountStatement;

use App\Models\AccountStatement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'operation_date' => 'required | date',
            'operation_number' => [
                'required',
                Rule::unique(AccountStatement::class)->ignore('operation_number'),
            ],
            'description' => 'required',
            'charge' => "required_without:payment",
            'payment' => "required_without:charge",
            'acData' => 'nullable|array',
            'acData.*' => 'integer',
            'scData' => 'nullable|array',
            'scData.*' => 'integer',
            'peData' => 'nullable|array',
            'peData.*' => 'integer',

        ];
    }
}
