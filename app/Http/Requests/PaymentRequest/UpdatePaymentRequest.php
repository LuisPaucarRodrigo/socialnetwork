<?php

namespace App\Http\Requests\PaymentRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
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
            'operation_number' => 'required|numeric',
            'date' => 'required|date',
            'state' => 'required|numeric',
            'payment_doc' => 'required|mimes:png,jpg,jpeg,pdf|max:2048',
            'payment_id' => 'required|numeric',
            'price_dolar' => 'nullable|numeric',
        ];
    }
}
