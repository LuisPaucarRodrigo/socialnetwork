<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignUsersRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'archive_id' => 'required|exists:archives,id',
            'users' => 'required|array|min:3',
            'due_date' => 'required|date',
            'users.*.id' => 'required|exists:users,id',
            'users.*.area_id' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'archive_id.required' => 'El campo archive_id es obligatorio.',
            'archive_id.exists' => 'El archivo especificado no existe.',
            'users.required' => 'Debe seleccionar al menos 3 usuarios.',
            'users.array' => 'El campo users debe ser un array.',
            'users.min' => 'Debe seleccionar al menos 3 usuarios.',
            'users.*.id.required' => 'Cada usuario debe tener un ID.',
            'users.*.id.exists' => 'El usuario especificado no existe.',
            'users.*.area_id.required' => 'Cada usuario debe tener un área asignada.',
            'users.*.area_id.integer' => 'El área asignada debe ser un número entero.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
             // Solo ejecutar si no hay errores previos
                $users = $this->input('users', []);

                $area1Count = 0;
                $area7Count = 0;

                foreach ($users as $user) {
                    if (isset($user['area_id'])) {
                        if ($user['area_id'] == 1) {
                            $area1Count++;
                        }
                        if ($user['area_id'] == 7) {
                            $area7Count++;
                        }
                    }
                }

                if ($area1Count < 1) {
                    $validator->errors()->add('users', 'Debe seleccionar al menos un usuario con área de Gerencia.');
                }

                if ($area7Count < 1) {
                    $validator->errors()->add('users', 'Debe seleccionar al menos un usuario con área de Calidad.');
                }
            
        });
    }
}
