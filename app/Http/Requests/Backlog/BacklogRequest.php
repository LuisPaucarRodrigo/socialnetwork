<?php

namespace App\Http\Requests\Backlog;

use Illuminate\Foundation\Http\FormRequest;

class BacklogRequest extends FormRequest
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
            'backlog_site_id' => 'nullable',
            'activity_type' => 'nullable',
            'task_id' => 'nullable',
            'system' => 'nullable',
            'subsystem' => 'nullable',
            'element' => 'nullable',
            'quantity' => 'nullable',
            'event_date' => 'nullable',
            'report_date' => 'nullable',
            'event_element_description' => 'nullable',
            'state' => 'nullable',
            'commitment' => 'nullable',
            'c_start_date' => 'nullable',
            'c_end_date' => 'nullable',
            'responsible' => 'nullable',
            'responsible_cicsa' => 'nullable',
            'criticity' => 'nullable',
            'conproco_area' => 'nullable',
            'origin_event' => 'nullable',
            'report_by' => 'nullable',
            'report' => 'nullable',
            'email_send' => 'nullable',
            'budget' => 'nullable',
            'budget_required' => 'nullable',
            'budget_2' => 'nullable',
        ];
    }
}
