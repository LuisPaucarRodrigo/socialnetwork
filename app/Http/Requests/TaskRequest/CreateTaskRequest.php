<?php

namespace App\Http\Requests\TaskRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Project;
use Carbon\Carbon;
class CreateTaskRequest extends FormRequest
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
        $project_id = $this->input('project_id');
        $project = Project::find($project_id);

        return [
            'project_id' => 'required|exists:projects,id',
            'task' => 'required|string',
            'percentage' => 'required|integer|min:0|max:100',
            'start_date' => ['required', 'date', function ($attribute, $value, $fail) use ($project) {
                $startDate = Carbon::parse($value)->startOfDay();
                $projectStartDate = Carbon::createFromFormat('d/m/Y', $project->start_date)->startOfDay();
                $projectEndDate = Carbon::createFromFormat('d/m/Y', $project->end_date)->startOfDay();

                if ($startDate > $projectEndDate) {
                    $fail(__('La fecha de inicio debe ser inferior o igual a la fecha de fin del proyecto'));
                } else if ($startDate < $projectStartDate) {
                    $fail(__('La fecha de inicio debe ser mayor o igual a la fecha de inicio del proyecto'));
                }
            }],
            'end_date' => ['required', 'date', 'after_or_equal:start_date', function ($attribute, $value, $fail) use ($project) {
                $endDate = Carbon::parse($value)->startOfDay();
                $projectStartDate = Carbon::createFromFormat('d/m/Y', $project->start_date)->startOfDay();
                $projectEndDate = Carbon::createFromFormat('d/m/Y', $project->end_date)->startOfDay();

                if ($endDate > $projectEndDate) {
                    $fail(__('La fecha de fin debe ser inferior o igual a la fecha de fin del proyecto'));
                } else if ($endDate < $projectStartDate) {
                    $fail(__('La fecha de fin debe ser mayor o igual a la fecha de inicio del proyecto'));
                }
            }],
            'status' => 'required|in:pendiente,proceso,completado,detenido',
        ];
    }
}
