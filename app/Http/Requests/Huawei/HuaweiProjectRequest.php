<?php

namespace App\Http\Requests\Huawei;

use Illuminate\Foundation\Http\FormRequest;

class HuaweiProjectRequest extends FormRequest
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
        $isUpdate = $this->method() === 'PUT' || $this->method() === 'PATCH' || $this->has('id');

        return [
            'assigned_diu' => [
                $isUpdate ? 'nullable' : 'required',
                'unique:huawei_projects,assigned_diu' . ($isUpdate && $this->id ? ",{$this->id}" : '')
            ],
            'cost_center_id' => $isUpdate ? 'nullable' : 'required',
            'macro_project' => $isUpdate ? 'nullable' : 'required',
            'prefix' => $isUpdate ? 'nullable' : 'required',
            'huawei_site_id' => $isUpdate ? 'nullable' : 'required',
            'zone' => $isUpdate ? 'nullable' : 'required',
            'assignation_date' => $isUpdate ? 'nullable|date' : 'required|date',
            'description' => 'nullable',

            'assignations' => 'required|array|min:1',
            'assignations.*.assignation_date' => 'required|date',
            'assignations.*.description' => 'required|string',
            'assignations.*.po' => 'required|string',
            'assignations.*.index' => 'nullable|string',
            'assignations.*.id' => 'nullable',

            'assignations.*.base_lines' => 'required|array|min:1',
            'assignations.*.base_lines.*.id' => 'nullable',
            'assignations.*.base_lines.*.description' => 'required|string',
            'assignations.*.base_lines.*.code' => 'required|string',
            'assignations.*.base_lines.*.quantity' => 'required|numeric',
            'assignations.*.base_lines.*.observation' => 'nullable|string',
            'assignations.*.base_lines.*.evidence' => 'nullable|string',
            'assignations.*.base_lines.*.goal' => 'nullable|string',
            'assignations.*.base_lines.*.unit_price' => 'required|numeric',
            'assignations.*.base_lines.*.unit' => 'required|string',

            'schedule' => 'required|array|min:1',
            'schedule.*.id' => 'nullable',
            'schedule.*.employees' => 'required|array|min:1',
            'schedule.*.start_date' => 'required|date',
            'schedule.*.end_date' => 'required|date|after_or_equal:schedule.*.start_date',
            'schedule.*.days' => 'required|numeric',
            'schedule.*.activity' => 'required|string',
        ];
    }

}
