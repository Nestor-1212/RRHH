<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidatoEstudio extends Model
{
    protected $table = 'candidato_estudios';

    protected $fillable = [
        'candidato_id', 'nivel', 'institucion', 'carrera',
        'anio_inicio', 'anio_fin', 'graduado',
    ];

    protected $casts = ['graduado' => 'boolean'];

    public function candidato()
    {
        return $this->belongsTo(Candidato::class);
    }

    public function getNivelLabelAttribute(): string
    {
        return match($this->nivel) {
            'primaria'      => 'Primaria',
            'secundaria'    => 'Secundaria',
            'bachillerato'  => 'Bachillerato',
            'tecnico'       => 'Técnico',
            'universitario' => 'Universitario',
            'postgrado'     => 'Postgrado',
            'maestria'      => 'Maestría',
            'doctorado'     => 'Doctorado',
            default         => $this->nivel,
        };
    }
}
