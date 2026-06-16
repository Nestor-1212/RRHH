<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    protected $table = 'evaluaciones';

    protected $fillable = [
        'empleado_id', 'evaluador_id', 'periodo',
        'puntaje_productividad', 'puntaje_responsabilidad',
        'puntaje_trabajo_equipo', 'puntaje_calidad',
        'puntaje_cumplimiento', 'puntaje_total',
        'calificacion', 'comentarios',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function evaluador()
    {
        return $this->belongsTo(User::class, 'evaluador_id');
    }

    public function getCalificacionBadgeAttribute(): string
    {
        return match($this->calificacion) {
            'excelente'  => 'success',
            'bueno'      => 'primary',
            'regular'    => 'warning',
            'deficiente' => 'danger',
            default      => 'secondary',
        };
    }
}
