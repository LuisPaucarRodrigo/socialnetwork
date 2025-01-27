<?php

namespace App\Http\Requests\Cicsa;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateChargeArea extends FormRequest
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
            'invoice_number' => 'nullable',
            'invoice_date' => 'nullable',
            'credit_to' => 'nullable|min:0',
            'deposit_date' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail){
                    if ($value && Carbon::parse($value)->lt(Carbon::parse($this->invoice_date))) {
                        $fail('La fecha de abono debe ser mayor o igual que la fecha de la factura.');
                    }
                }
            ],
            'document' => 'nullable',
            'amount' => 'required',
            'state_detraction' => 'required|string',
            'transaction_number_current' => 'nullable',
            'checking_account_amount' => 'nullable|string',
            'deposit_date_bank' => 'nullable|date',
            'transaction_number_bank' => 'nullable',
            'amount_bank' => 'nullable|numeric',
            'user_name' => 'required',
            'user_id' => 'required',
        ];
    }
}
