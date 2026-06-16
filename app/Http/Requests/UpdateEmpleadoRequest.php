<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmpleadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['Super Admin', 'Recursos Humanos']);
    }

    public function rules(): array
    {
        $id = $this->route('empleado')?->id;

        return [
            'nombre'               => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-\.]+$/u'],
            'apellido'             => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-\.]+$/u'],
            'cedula'               => ['required', 'string', 'max:30', "unique:empleados,cedula,{$id}"],
            'fecha_nacimiento'     => ['nullable', 'date', 'before:-18 years'],
            'fecha_ingreso'        => ['required', 'date', 'before_or_equal:today'],
            'cargo'                => ['required', 'string', 'max:150'],
            'departamento_id'      => ['required', 'exists:departamentos,id'],
            'jefe_id'              => ['nullable', 'exists:empleados,id', "different:empleados.id.{$id}"],
            'tipo_contrato'        => ['required', 'in:indefinido,definido,servicios,pasantia'],
            'salario'              => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'jornada'              => ['required', 'in:completa,parcial,mixta,nocturna'],
            'horario'              => ['nullable', 'string', 'max:100'],
            'telefono'             => ['nullable', 'string', 'max:20'],
            'email'                => ['nullable', 'email:rfc', 'max:255'],
            'direccion'            => ['nullable', 'string', 'max:500'],
            'contacto_emergencia'  => ['nullable', 'string', 'max:150'],
            'telefono_emergencia'  => ['nullable', 'string', 'max:20'],
            'foto'                 => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:min_width=100,min_height=100'],
            'estado'               => ['sometimes', 'in:activo,inactivo,retirado'],
        ];
    }
}
