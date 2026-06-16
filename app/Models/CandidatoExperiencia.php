<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidatoExperiencia extends Model
{
    protected $table = 'candidato_experiencias';

    protected $fillable = [
        'candidato_id', 'empresa', 'cargo', 'fecha_inicio',
        'fecha_fin', 'actualmente', 'descripcion',
    ];

    protected $casts = [
        'fecha_inicio'  => 'date',
        'fecha_fin'     => 'date',
        'actualmente'   => 'boolean',
    ];

    public function candidato()
    {
        return $this->belongsTo(Candidato::class);
    }
}
