<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidatoIdioma extends Model
{
    protected $table = 'candidato_idiomas';

    protected $fillable = ['candidato_id', 'idioma', 'nivel'];

    public function candidato()
    {
        return $this->belongsTo(Candidato::class);
    }
}
