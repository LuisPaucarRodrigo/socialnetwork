<?php

namespace App\Http\Requests\ChecklistRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class ChecklistDailytoolkitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    // 2|WdbGZmqlbe9pMrFkFpGcKLCa5EpqQ8JaE1jOZb5ibff8a74b
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'personal_2' => 'nullable',
            'zone' => 'required',
            'power_meter' => 'required',
            'ammeter_clamp' => 'required',
            'cutting_pliers' => 'required',
            'nose_pliers' => 'required',
            'universal_pliers' => 'required',
            'tape' => 'required',
            'cutter' => 'required',
            'knob_driver' => 'required',
            'screwdriver_set' => 'required',
            'allenkeys_set' => 'required',
            'thor_screwboard' => 'required',
            'helmet_flashlight' => 'required',
            'freanch_key' => 'required',
            'pyrometer' => 'required',
            'laptop' => 'required',
            'console_cables' => 'required',
            'network_adapter' => 'required',
            'observations' => 'nullable',
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
