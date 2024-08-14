<?php

namespace App\Http\Requests\ChecklistRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class ChecklistCarRequest extends FormRequest
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
            'reason'=> 'required',
            'additionalEmployees'=> 'nullable',
            'zone'=> 'required',
            'km'=> 'required',
            'plate'=> 'required',
            'circulation'=> 'required',
            'technique'=> 'required',
            'soat'=> 'required',
            'hornState'=> 'required',
            'brakesState'=> 'required',
            'headlightsState'=> 'required',
            'indicatorsState'=> 'required',
            'mirrorsState'=> 'required',
            'tiresState'=> 'required',
            'bumpersState'=> 'required',
            'temperatureGauge'=> 'required',
            'oilGauge'=> 'required',
            'fuelGauge'=> 'required',
            'vehicleCleanliness'=> 'required',
            'doorsState'=> 'required',
            'windshieldState'=> 'required',
            'engineState'=> 'required',
            'batteryState'=> 'required',
            'extinguisher'=> 'required',
            'firstAidKit'=> 'required',
            'cones'=> 'required',
            'jack'=> 'required',
            'spareTire'=> 'required',
            'towCable'=> 'required',
            'batteryCable'=> 'required',
            'reflector'=> 'required',
            'emergencyKit'=> 'required',
            'alarm'=> 'required',
            'chocks'=> 'required',
            'rolloverProtection'=> 'required',
            'ladderHolder'=> 'required',
            'sidePlate'=> 'required',

            'front'=> 'required',
            'leftSide'=> 'required',
            'rightSide'=> 'required',
            'interior'=> 'required',
            'rearLeftTire'=> 'required',
            'rearRightTire'=> 'required',
            'frontRightTire'=> 'required',
            'frontLeftTire'=> 'required',
            'observation'=> 'nullable',
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
