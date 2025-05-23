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
            'operation_date' => 'required | date | before_or_equal:now',
            'operation_number' => [
                'nullable', 
                'size:6',
                Rule::unique(AccountStatement::class)
                    ->ignore($this->id)
                    ->where(function ($query) {
                        return $query->where('operation_date', $this->operation_date);
                    }),
            ],
            'description' => 'required',
            'charge' => 'required_without:payment',
            'payment' => 'required_without:charge',
            'geData' => 'nullable|array',
            'geData.*' => 'integer',
            // 'scData' => 'nullable|array',
            // 'scData.*' => 'integer',
            // 'peData' => 'nullable|array',
            // 'peData.*' => 'integer',
            // 'spData' => 'nullable|array',
            // 'spData.*' => 'integer',
        ];
    }
}
