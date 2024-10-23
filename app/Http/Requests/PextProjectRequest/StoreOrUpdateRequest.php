<?php

namespace App\Http\Requests\PextProjectRequest;

use App\Models\PextProjectExpense;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateRequest extends FormRequest
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
            'expense_type' => 'required|string',
            'ruc' => ['required','numeric','digits:11',
                function ($attribute, $value, $fail) {
                    $expenseId = $this->route('expense_id');
                    if ($this->doc_number) {
                        $exists = PextProjectExpense::where('ruc', $value)
                            ->where('doc_number', $this->doc_number)
                            ->when($expenseId, function ($query, $expenseId) {
                                return $query->where('id', '!=', $expenseId);
                            })
                            ->exists();

                        if ($exists) {
                            $fail('El RUC y el número de documento ya existen juntos.');
                        }
                    }
                }
            ],
            'type_doc' => 'required|string|in:Efectivo,Deposito,Factura,Boleta,Voucher de Pago',
            'operation_number' => 'nullable|numeric',
            'operation_date' => 'nullable|date',
            'doc_number' => 'nullable|numeric',
            'doc_date' => 'sometimes|required|date',
            'amount' => 'required|string',
            'zone' => 'required',
            'provider_id' => 'nullable',
            'description' => 'required|string',
            'photo' => 'nullable',
            'state' => 'required|string',
            'igv' => 'required',
            'pext_project_id' => 'required',
            'cicsa_assignation_id' => 'required'
        ];
    }
}
