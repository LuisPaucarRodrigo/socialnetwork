<?php

namespace App\Http\Requests\PextProjectRequest;

use App\Models\PextProjectExpense;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateExpenseRequest extends FormRequest
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
            'fixedOrAdditional' => 'required|string',
            'expense_type' => 'required|string',
            'ruc' => [
                'required',
                'numeric',
                'digits:11',
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
            'type_doc' => 'required|string|in:Efectivo,Factura,Boleta,Voucher de Pago,Yape-Plin,Sin Comprobante',
            'operation_number' => 'nullable|string|min:6',
            'operation_date' => 'nullable|date',
            'doc_number' => 'nullable|string',
            'doc_date' => 'sometimes|required|date',
            'amount' => 'required|string',
            'zone' => 'required',
            'provider_id' => 'nullable',
            'description' => 'required|string',
            'photo' => 'nullable|max:2048',
            'is_accepted' => 'required|string',
            'igv' => 'required',
            'project_id' => 'required'
        ];
    }
}
