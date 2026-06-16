<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEvaluacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['Super Admin', 'Recursos Humanos']);
    }

    public function rules(): array
    {
        return [
            'empleado_id'             => ['required', 'exists:empleados,id'],
            'periodo'                 => [
                'required', 'string', 'max:100',
                Rule::unique('evaluaciones')->where(fn($q) =>
                    $q->where('empleado_id', $this->empleado_id)
                ),
            ],
            'puntaje_productividad'   => ['required', 'integer', 'min:0', 'max:20'],
            'puntaje_responsabilidad' => ['required', 'integer', 'min:0', 'max:20'],
            'puntaje_trabajo_equipo'  => ['required', 'integer', 'min:0', 'max:20'],
            'puntaje_calidad'         => ['required', 'integer', 'min:0', 'max:20'],
            'puntaje_cumplimiento'    => ['required', 'integer', 'min:0', 'max:20'],
            'comentarios'             => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'periodo.unique' => 'Ya existe una evaluación para este empleado en el período indicado.',
            '*.min'          => 'El puntaje mínimo es 0.',
            '*.max'          => 'El puntaje máximo por criterio es 20.',
        ];
    }
}
