<?php

namespace App\Http\Requests\CostsRequest;

use App\Models\AdditionalCost;
use App\Constants\PintConstants;
use App\Models\StaticCost;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Project;
use App\Models\Preproject;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
class AdditionalCostsApiRequest extends FormRequest
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

        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d');
        $isStatic = $this->input('expense_type') === PintConstants::COMBUSTIBLE_UM;
        $isGEP = $this->input('expense_type') === PintConstants::COMBUSTIBLE_GEP;
        $isAdditional = !$isStatic && !$isGEP;

        
        //MantoPINT
        if ($isStatic || $isAdditional) {
            $preprojectId = Preproject::where('date', '>=', $startOfMonth)
                ->where('cost_center_id', 1)
                ->where('cost_line_id', 1)
                ->where('date', '<=', $endOfMonth)
                ->where('customer_id', 1)
                ->select('id')
                ->first();
            if (!$preprojectId) {
                throw new HttpResponseException(
                    response()->json(['error' => 'No se encontraron preproyectos para este mes.'], 404)
                );
            }
            $projectId = Project::where('preproject_id', $preprojectId->id)->first();
            if (!$projectId) {
                throw new HttpResponseException(
                    response()->json(['error' => 'No se encontraron proyectos relacionados al preproyecto.'], 404)
                );
            }
        }
    
        // Manejo de Preproject y Project para GEP
        if ($isGEP) {
            $preprojectId = Preproject::where('date', '>=', $startOfMonth)
                ->where('cost_center_id', 2)
                ->where('cost_line_id', 1)
                ->where('date', '<=', $endOfMonth)
                ->where('customer_id', 1)
                ->select('id')
                ->first();
            if (!$preprojectId) {
                throw new HttpResponseException(
                    response()->json(['error' => 'No se encontraron preproyectos GEP para este mes.'], 404)
                );
            }
            $projectId = Project::where('preproject_id', $preprojectId->id)->first();
            if (!$projectId) {
                throw new HttpResponseException(
                    response()->json(['error' => 'No se encontraron proyectos relacionados al preproyecto GEP.'], 404)
                );
            }
        }
        //Validation Rules
        $rules = [
            'expense_type' => 'required|string',
            'ruc' => 'required|numeric|digits:11',
            'type_doc' => 'required|string|in:Sin Comprobante,Deposito,Factura,Boleta,Voucher de Pago',
            'doc_number' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) use ($projectId, $isAdditional, $isStatic, $isGEP) {
                    if ($value != null && $value != '' && $value != 'S/N') {
                        $numberOfElements = 0;
                        if ($isAdditional){
                            $numberOfElements = AdditionalCost::where('project_id', $projectId->id)
                                ->where('doc_number', $value)->count();
                        }
                        if ($isGEP || $isAdditional){
                            $numberOfElements = StaticCost::where('project_id', $projectId->id)
                                ->where('doc_number', $value)->count();
                        }
                        if ($numberOfElements > 0) {
                            $fail(__('Número de documento repetido'));
                        }
                    }
                }
            ],
            'doc_date' => [
                'sometimes',
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $doc_date = Carbon::createFromFormat('d/m/Y', $value);
                    $now = Carbon::now();
                    if ($doc_date->lt($now->subDays(3))) {
                        $fail('Solo dos días de atraso');
                    }
                }
            ],
            'amount' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) use ($projectId) {
                    $remaining_budget = $projectId->getRemainingBudgetAttribute();
                    if ($projectId->cost_center_id == 1 && $value > $remaining_budget) {
                        $fail(__('El monto del gasto excede el presupuesto restante. S/. ' . number_format($remaining_budget, 2)));
                    }
                }
            ],
            'zone' => 'required',
            'provider_id' => 'nullable',
            'description' => 'required|string',
            'photo' => 'nullable',
        ];

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

