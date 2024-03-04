<?php

namespace App\Http\Requests\PurchaseOrderRequest;

use Illuminate\Foundation\Http\FormRequest;

class CreatePurchaseOrderRequest extends FormRequest
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
            'state' => 'required|string',
            'facture_doc' => 'required|mimes:pdf|max:2048',
            'facture_date' => 'required|date',
            'facture_number' => 'required|numeric',
            'remission_guide_doc'=> 'required|mimes:pdf|max:2048',
            'remission_guide_date' => 'required|date',
            'remission_guide_number' => 'required|numeric',
        ];
    }
}
