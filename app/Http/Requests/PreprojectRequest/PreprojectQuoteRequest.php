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
            "preproject_products" => ['required_without_all:products,items', 'array'],
            "items" => ['required_without_all:products,preproject_products', 'array'],
            "products" => ['required_without_all:items,preproject_products', 'array'],
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
            'items.required_without_all' => 'Debes proporcionar al menos uno entre servicios o productos.',
            'products.required_without_all' => 'Debes proporcionar al menos uno entre servicios o productos.',
            'preproject_products.required_without_all' => 'Debes proporcionar al menos uno entre servicios o productos.',
        ];
    }
}
