<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpleadoDocumento extends Model
{
    protected $table = 'empleado_documentos';

    protected $fillable = ['empleado_id', 'tipo', 'nombre', 'archivo'];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function getTipoLabelAttribute(): string
    {
        return match($this->tipo) {
            'contrato'      => 'Contrato',
            'cedula'        => 'Cédula',
            'seguro_social' => 'Seguro Social',
            'certificado'   => 'Certificado',
            'titulo'        => 'Título',
            'otro'          => 'Otro',
            default         => $this->tipo,
        };
    }
}
