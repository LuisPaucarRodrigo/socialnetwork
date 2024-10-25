<?php

namespace App\Http\Requests\CostsRequest;

use App\Models\AdditionalCost;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Project;
use App\Models\Preproject;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
class  AdditionalCostsApiRequest extends FormRequest
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
        $project = Project::find($this->input('project_id'));
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d');

        $preprojectId = Preproject::where('date', '>=', $startOfMonth)
            ->where('date', '<=', $endOfMonth)
            ->where('customer_id', 1)
            ->select('id')
            ->first();
        $projectId = Project::where('preproject_id', $preprojectId->id)->select('id')->first();
        if (!$projectId) {
            return response()->json([
                'error' => "No se encontraron preproyectos pint para este mes."
            ], 404);
        }

        $rules = [
            'expense_type' => 'required|string',
            'ruc' => 'required|numeric|digits:11',
            'type_doc' => 'required|string|in:Efectivo,Deposito,Factura,Boleta,Voucher de Pago',
            'doc_number' => ['nullable', 'string', function($attribute, $value, $fail) use ($projectId) {
                if ($value != null && $value != '' && $value != 'S/N' ) {
                    $numberOfElements = AdditionalCost::where('project_id', $projectId->id)->where('doc_number', $value)->count();
                    if ($numberOfElements > 0) {
                        $fail(__('Número de documento repetido'));
                    }
                }
            }],
            'doc_date' => ['sometimes', 'required', 'string', function($attribute, $value, $fail) {
                $doc_date = Carbon::createFromFormat('d/m/Y', $value);
                $now = Carbon::now();
                if ($doc_date->lt($now->subDays(3))) {
                    $fail('Solo dos días de atraso');
                }
            }],
            'amount' => 'required|numeric',
            'zone' => 'required',
            'provider_id' => 'nullable',
            'description' => 'required|string',
            'photo' => 'nullable',
        ];

        if ($this->has('project_id')) {
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

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(
            response()->json([
                'status' => 'fail',
                'error' => $errors
            ], 422)
        );
    }
}

