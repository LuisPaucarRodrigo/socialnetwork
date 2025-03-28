<?php

namespace App\Http\Requests\Cicsa;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateInstallationRequest extends FormRequest
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
            "pext_date" => "required",
            "pint_date" => "required",
            "pint_amount" => "required|numeric",
            "pext_amount" => "required|numeric",
            "projected_amount" => "nullable|numeric",
            "conformity" => "required",
            "report" => "required",
            "shipping_report_date" => "nullable",
            'coordinator' => 'required|string',
            'observation' => 'nullable|string',
            "user_name" => "required",
            "user_id" => "required",
            "cicsa_assignation_id" => "required",
            "total_materials" => "nullable|array",
        ];
    }
}
