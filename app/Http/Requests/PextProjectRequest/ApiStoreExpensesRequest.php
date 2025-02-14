<?php

namespace App\Http\Requests\PextProjectRequest;

use App\Models\PextProjectExpense;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class ApiStoreExpensesRequest extends FormRequest
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
            'ruc' => ['required','string','size:11',
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
            'type_doc' => 'required|string',
            'doc_number' => 'nullable|string',
            'doc_date' => 'sometimes|required|string',
            'amount' => 'required|string',
            'zone' => 'required',
            'description' => 'required|string',
            'photo' => 'nullable',
            'project_id' => 'required'
        ];
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
