<?php

namespace App\Http\Requests\Cicsa;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateServiceOrder extends FormRequest
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
            'service_order_date' => 'required',
            'service_order' => 'required',
            'estimate_sheet' => 'required',
            'purchase_order' => 'required',
            'pdf_invoice' => 'required',
            'zip_invoice' => 'required',
            'document' => 'required',
            'document_invoice' => 'nullable',
            'user_name' => 'required',
            'user_id' => 'required',
        ];
    }
}
