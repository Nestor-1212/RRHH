<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAmonestacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['Super Admin', 'Recursos Humanos']);
    }

    public function rules(): array
    {
        return [
            'empleado_id'    => ['required', 'exists:empleados,id'],
            'fecha'          => ['required', 'date', 'before_or_equal:today'],
            'tipo'           => ['required', 'in:llamado_atencion,verbal,escrita,suspension'],
            'motivo'         => ['required', 'string', 'max:255'],
            'descripcion'    => ['required', 'string', 'max:3000'],
            'dias_suspension'=> ['required_if:tipo,suspension', 'integer', 'min:1', 'max:30'],
            'archivos.*'     => ['nullable', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,doc,docx'],
        ];
    }

    public function messages(): array
    {
        return [
            'dias_suspension.required_if' => 'Debe indicar los días de suspensión.',
            'archivos.*.mimes'            => 'Los archivos deben ser PDF, imágenes o documentos Word.',
            'archivos.*.max'              => 'Cada archivo no puede superar los 10 MB.',
        ];
    }
}
