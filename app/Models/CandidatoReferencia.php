<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidatoReferencia extends Model
{
    protected $table = 'candidato_referencias';

    protected $fillable = ['candidato_id', 'nombre', 'relacion', 'telefono', 'email'];

    public function candidato()
    {
        return $this->belongsTo(Candidato::class);
    }
}
