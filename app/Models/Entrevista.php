<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrevista extends Model
{
    protected $fillable = [
        'candidato_id', 'vacante_id', 'entrevistador_id', 'fecha_entrevista',
        'tipo', 'puntaje_experiencia', 'puntaje_conocimiento', 'puntaje_comunicacion',
        'puntaje_actitud', 'puntaje_disponibilidad', 'puntaje_total',
        'comentarios', 'fortalezas', 'debilidades', 'resultado',
        'motivo_rechazo', 'detalle_rechazo',
    ];

    protected $casts = ['fecha_entrevista' => 'datetime'];

    public function candidato()
    {
        return $this->belongsTo(Candidato::class);
    }

    public function vacante()
    {
        return $this->belongsTo(Vacante::class);
    }

    public function entrevistador()
    {
        return $this->belongsTo(User::class, 'entrevistador_id');
    }

    public function calcularPuntajeTotal(): int
    {
        return $this->puntaje_experiencia
            + $this->puntaje_conocimiento
            + $this->puntaje_comunicacion
            + $this->puntaje_actitud
            + $this->puntaje_disponibilidad;
    }

    public function getResultadoBadgeAttribute(): string
    {
        return match($this->resultado) {
            'seleccionado'    => 'success',
            'no_seleccionado' => 'danger',
            default           => 'warning',
        };
    }

    public function getTipoLabelAttribute(): string
    {
        return match($this->tipo) {
            'inicial'  => 'Inicial',
            'tecnica'  => 'Técnica',
            'final'    => 'Final',
            default    => $this->tipo,
        };
    }
}
