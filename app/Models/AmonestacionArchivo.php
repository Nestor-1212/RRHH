<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmonestacionArchivo extends Model
{
    protected $table = 'amonestacion_archivos';

    protected $fillable = ['amonestacion_id', 'tipo', 'nombre', 'archivo'];

    public function amonestacion()
    {
        return $this->belongsTo(Amonestacion::class);
    }
}
