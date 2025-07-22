<?php

namespace App\Http\Requests\ShoppingArea\PaymentApproval;

use Illuminate\Foundation\Http\FormRequest;

class PaymentApprovalRequest extends FormRequest
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
            'zone' => 'required',
            'description' => 'required',
            'amount' => 'required|numeric',
            'account_number' => 'required',
            'bank' => 'required',
            'ruc' => 'required|size:11',
            'beneficiary' => 'required',
            'document' => 'nullable|file',
            'cost_line_id' => 'required',
        ];
    }
}
