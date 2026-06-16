<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    protected $fillable = [
        'empleado_id', 'candidato_id', 'fecha', 'tipo',
        'descripcion', 'referencia_type', 'referencia_id',
    ];

    protected $casts = ['fecha' => 'date'];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function candidato()
    {
        return $this->belongsTo(Candidato::class);
    }

    public function referencia()
    {
        return $this->morphTo();
    }

    public function getIconoAttribute(): string
    {
        return match($this->tipo) {
            'candidatura'    => 'bi-person-plus',
            'entrevista'     => 'bi-chat-dots',
            'contratacion'   => 'bi-file-check',
            'ingreso'        => 'bi-door-open',
            'aumento_salarial' => 'bi-cash-coin',
            'amonestacion'   => 'bi-exclamation-triangle',
            'evaluacion'     => 'bi-clipboard-check',
            'salida'         => 'bi-door-closed',
            default          => 'bi-circle',
        };
    }

    public function getColorAttribute(): string
    {
        return match($this->tipo) {
            'candidatura'    => '#6c757d',
            'entrevista'     => '#0dcaf0',
            'contratacion'   => '#0d6efd',
            'ingreso'        => '#198754',
            'aumento_salarial' => '#20c997',
            'amonestacion'   => '#ffc107',
            'evaluacion'     => '#6610f2',
            'salida'         => '#dc3545',
            default          => '#adb5bd',
        };
    }
}
