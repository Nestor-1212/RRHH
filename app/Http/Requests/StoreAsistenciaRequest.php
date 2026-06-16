<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAsistenciaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['Super Admin', 'Recursos Humanos', 'Jefe Departamento']);
    }

    public function rules(): array
    {
        return [
            'empleado_id'  => ['required', 'exists:empleados,id'],
            'fecha'        => [
                'required', 'date', 'before_or_equal:today',
                Rule::unique('asistencias')->where(fn($q) =>
                    $q->where('empleado_id', $this->empleado_id)
                ),
            ],
            'tipo'         => ['required', 'in:normal,tardanza,ausencia,permiso,vacacion,feriado'],
            'hora_entrada' => ['nullable', 'date_format:H:i', 'required_if:tipo,normal,tardanza'],
            'hora_salida'  => ['nullable', 'date_format:H:i', 'after:hora_entrada'],
            'observaciones'=> ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'fecha.unique'              => 'Ya existe un registro de asistencia para este empleado en esta fecha.',
            'hora_entrada.required_if'  => 'La hora de entrada es obligatoria para este tipo de asistencia.',
            'hora_salida.after'         => 'La hora de salida debe ser posterior a la hora de entrada.',
        ];
    }
}
