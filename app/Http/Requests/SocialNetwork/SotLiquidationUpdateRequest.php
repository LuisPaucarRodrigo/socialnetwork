<?php

namespace App\Http\Requests\SocialNetwork;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\SNSotLiquidation; // Asegúrate de importar el modelo correspondiente

class SotLiquidationUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Cambia esto según tus requisitos de autorización
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sot_id' => 'required',
            'up_minutes' => [
                'required',
                function ($attribute, $value, $fail) {
                    $sotLiquidation = SNSotLiquidation::find($this->input('id'));

                    if ($sotLiquidation && $sotLiquidation->up_minutes === 'Ok' && $value !== 'Ok') {
                        $fail('No se puede modificar mientras su valor actual sea "Ok".');
                    }
                }
            ],
            'liquidation' => [
                'required',
                function ($attribute, $value, $fail) {
                    $sotLiquidation = SNSotLiquidation::find($this->input('id'));

                    if ($sotLiquidation && $sotLiquidation->liquidation === 'Ok' && $value !== 'Ok') {
                        $fail('No se puede modificar mientras su valor actual sea "Ok".');
                    }
                }
            ],
            'down_warehouse' => [
                'required',
                function ($attribute, $value, $fail) {
                    $sotLiquidation = SNSotLiquidation::find($this->input('id'));

                    if ($sotLiquidation && $sotLiquidation->down_warehouse === 'Ok' && $value !== 'Ok') {
                        $fail('No se puede modificar mientras su valor actual sea "Ok".');
                    }
                }
            ],
            'liquidation_date' => 'required',
            'sot_status' => [
                'required',
                function ($attribute, $value, $fail) {
                    $sotLiquidation = SNSotLiquidation::find($this->input('id'));

                    if ($sotLiquidation && $sotLiquidation->sot_status === 'Aceptado' && $value !== 'Aceptado') {
                        $fail('No se puede modificar mientras su valor actual sea "Aceptado".');
                    }
                }
            ]
        ];
    }
}
