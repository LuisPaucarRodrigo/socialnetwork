<?php

namespace App\Http\Requests\PreprojectRequest;

use Illuminate\Foundation\Http\FormRequest;

class PreprojectQuoteRequest extends FormRequest
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
            "name" => 'required',
            "date" => 'required',
            "supervisor" => 'required',
            "boss" => 'required',
            "rev" => 'required',
            "deliverable_time" => 'required',
            "validity_time" => 'required',
            'deliverable_place' => 'required',
            'payment_type' => 'required',
            "observations" => 'nullable',
            'preproject_id' => 'required',
            "items" => ['required_without:products', 'array'], 
            "products" => ['required_without:items', 'array'],
        ];
    }

    /**
     * Customize the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'items.required_without' => 'Debes proporcionar al menos uno entre items o productos.',
            'products.required_without' => 'Debes proporcionar al menos uno entre items o productos.',
        ];
    }
}
