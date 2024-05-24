<?php

namespace App\Http\Requests\SocialNetwork;

use Illuminate\Foundation\Http\FormRequest;

class CreateAndpdatePayment extends FormRequest
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
            'sot_bill' => 'nullable|string',
            'sot_bill_date' => 'nullable|date',
            'bill' => 'nullable|string',
            'bill_date' => 'nullable|date',
            'charge' => 'nullable|string',
            'charge_date' => 'nullable|date',
        ];
    }
}
