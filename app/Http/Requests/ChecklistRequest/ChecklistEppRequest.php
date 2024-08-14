<?php

namespace App\Http\Requests\ChecklistRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class ChecklistEppRequest extends FormRequest
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
            'helmet' => 'required',
            'chin_strap' => 'required',
            'windbreaker' => 'required',
            'vest' => 'required',
            'safety_shoes' => 'required',
            'tshirt_ls' => 'required',
            'shirt_ls' => 'required',
            'jeans' => 'required',
            'coveralls' => 'required',
            'jacket' => 'required',
            'dark_glasses' => 'required',
            'clear_glasses' => 'required',
            'overglasses' => 'required',
            'dust_mask' => 'required',
            'earplugs' => 'required',
            'latex_oil_gloves' => 'required',
            'nitrile_oil_gloves' => 'required',
            'split_leather_gloves' => 'required',
            'precision_gloves' => 'required',
            'cut_resistant_gloves' => 'required',
            'double_lanyard' => 'required',
            'harness' => 'required',
            'positioning_lanyard' => 'required',
            'carabiners' => 'required',
            'ascenders' => 'required',
            'sunscreen' => 'required',
            'ccip' => 'required',
            'claro' => 'required',
            'vericom' => 'required',
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
