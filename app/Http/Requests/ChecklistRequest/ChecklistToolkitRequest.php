<?php

namespace App\Http\Requests\ChecklistRequest;

use Illuminate\Foundation\Http\FormRequest;

class ChecklistToolkitRequest extends FormRequest
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
            'carabiner'=> 'required',
            'wireStripper'=> 'required',
            'crimper'=> 'required',
            'terminalCrimper'=> 'required',
            'files'=> 'required',
            'allenKeys'=> 'required',
            'braveDices'=> 'required',
            'readlineKit'=> 'required',
            'impactWrench'=> 'required',
            'dielectricTools'=> 'required',
            'cuttingTools'=> 'required',
            'forceps'=> 'required',
            'straightWrench'=> 'required',
            'frenchWrench'=> 'required',
            'saw'=> 'required',
            'silicone'=> 'required',
            'pulley'=> 'required',
            'tapeMeasure'=> 'required',
            'sling'=> 'required',
            'kit'=> 'required',
            'drillBits'=> 'required',
            'punch'=> 'required',
            'extractor'=> 'required',
            'wrenchSet'=> 'required',
            'cutter'=> 'required',
            'hammer'=> 'required',
            'largeToolBag'=> 'required',
            'mediumToolBag'=> 'required',
            'fallProtectionCar'=> 'required',
            'harness'=> 'required',
            'pressureWasher'=> 'required',
            'blower'=> 'required',
            'megommeter'=> 'required',
            'earthTester'=> 'required',
            'perimeterMeter'=> 'required',
            'manometer'=> 'required',
            'pyrometer'=> 'required',
            'laptop'=> 'required',
            'drill'=> 'required',
            'compass'=> 'required',
            'inclinometer'=> 'required',
            'flashlight'=> 'required',
            'powerMeter'=> 'required',
            'glueGun'=> 'required',
            'solderingGun'=> 'required',
            'stepLadder'=> 'required',
            'sprayer'=> 'required',
            'rj45Connector'=> 'required',
            'networkConsole'=> 'required',
            'networkAdapter'=> 'required',
            'hotStick'=> 'required',
            'rope75'=> 'required',
            'ladder'=> 'required',
            'extensionCord'=> 'required',
            'longCable'=> 'required',
            'padlock'=> 'required',
            'chains'=> 'required',
            'hose'=> 'required',
            'corporatePhone'=> 'required',
            'observation'=> 'nullable',
            'badTools'=> 'nullable',
            'goodTools'=> 'nullable',
        ];
    }
}
