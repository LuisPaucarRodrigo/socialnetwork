<?php

namespace App\Http\Requests\PurchaseQuote;

use Illuminate\Foundation\Http\FormRequest;

class CreatePurchaseQuoteRequest extends FormRequest
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
            'provider' => 'required|string',
            'purchasing_request_id' => 'required|numeric',
            'amount' => 'required|string|numeric',
            'quote_deadline' => 'required|date',
            'response' => 'required|string',
            'purchase_image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ];
    }
}
