<?php

namespace App\Http\Requests\Cicsa;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdatePurchaseOrderRequest extends FormRequest
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
            'cicsa_assignation_id' => 'nullable|numeric',
            'oc_date' => 'nullable|date',
            'amount' => 'required|string',
            'oc_number' => 'nullable|string',
            'master_format' => 'required|string',
            'item3456' => 'required|string',
            'budget' => 'required|string',
            'document' => 'required',
            'observation' => 'nullable|string',
            'user_name' => 'required|string',
            'user_id' => 'required|numeric',
        ];
    }
}
