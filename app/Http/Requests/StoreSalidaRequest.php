<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSalidaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['Super Admin', 'Recursos Humanos']);
    }

    public function rules(): array
    {
        return [
            'empleado_id'    => [
                'required',
                'exists:empleados,id',
                Rule::unique('salidas', 'empleado_id'),
            ],
            'fecha_salida'   => ['required', 'date', 'before_or_equal:today'],
            'tipo'           => ['required', 'in:renuncia,despido,finalizacion_contrato,jubilacion,otro'],
            'ultimo_cargo'   => ['required', 'string', 'max:150'],
            'ultimo_salario' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'motivo'         => ['required', 'string', 'max:3000'],
            'documentos.*'   => ['nullable', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,doc,docx'],
        ];
    }

    public function messages(): array
    {
        return [
            'empleado_id.unique' => 'Este empleado ya tiene una salida registrada.',
        ];
    }
}
