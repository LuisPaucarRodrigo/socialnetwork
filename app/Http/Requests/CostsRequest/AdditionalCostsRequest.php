<?php

namespace App\Http\Requests\CostsRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Project;

class AdditionalCostsRequest extends FormRequest
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

        // Inicializar variables
        $rules = [
            'expense_type' => 'required|string',
            'ruc' => 'required|numeric|digits:11',
            'type_doc' => 'required|string|in:Efectivo,Deposito,Factura,Boleta,Voucher de Pago',
            'doc_number' => 'nullable|string',
            'doc_date' => 'sometimes|required|string',
            'amount' => 'required|numeric',
            'zone' => 'required',
            'provider_id' => 'nullable',
            'description' => 'required|string',
            'photo' => 'nullable',
            'igv' => 'required',
            'project_id' => 'nullable' // Cambiar 'required' a 'nullable'
        ];

        if ($this->has('project_id')) {
            $project = Project::find($this->input('project_id'));
            if ($project) {
                $remaining_budget = $project->getRemainingBudgetAttribute();
                $rules['amount'] = [
                    'required',
                    'numeric',
                    function ($attribute, $value, $fail) use ($remaining_budget) {
                        if ($value > $remaining_budget) {
                            $fail(__('El monto del gasto excede el presupuesto restante. S/. ' . number_format($remaining_budget, 2)));
                        }
                    }
                ];
            }
        }

        return $rules;
    }
}
