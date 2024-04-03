<?php

namespace App\Http\Requests\PurchaseQuoteRequest;

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
            'quote_deadline' => 'nullable|date|before_or_equal:due_date',
            'due_date' => 'nullable|date',
            'purchase_doc' => 'required|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx',
            'igv' => 'required',
            'deliverable_time' => 'required',
            'payment_type' => 'required',
            'account_number' => 'required',
            'provider_id' => 'required',
            'currency' => 'required',
            'purchasing_request_id' => 'required|numeric',
            'products' => 'required|array',
        ];
    }
}
