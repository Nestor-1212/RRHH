<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidatoHabilidad extends Model
{
    protected $table = 'candidato_habilidades';

    protected $fillable = ['candidato_id', 'habilidad', 'nivel'];

    public function candidato()
    {
        return $this->belongsTo(Candidato::class);
    }
}
