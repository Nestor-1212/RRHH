<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidato extends Model
{
    protected $fillable = [
        'nombre', 'apellido', 'cedula', 'fecha_nacimiento', 'direccion',
        'telefono', 'email', 'foto', 'hoja_vida', 'aspiracion_salarial',
        'estado', 'notas',
    ];

    protected $casts = ['fecha_nacimiento' => 'date'];

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    public function estudios()
    {
        return $this->hasMany(CandidatoEstudio::class);
    }

    public function experiencias()
    {
        return $this->hasMany(CandidatoExperiencia::class);
    }

    public function referencias()
    {
        return $this->hasMany(CandidatoReferencia::class);
    }

    public function idiomas()
    {
        return $this->hasMany(CandidatoIdioma::class);
    }

    public function habilidades()
    {
        return $this->hasMany(CandidatoHabilidad::class);
    }

    public function entrevistas()
    {
        return $this->hasMany(Entrevista::class);
    }

    public function empleado()
    {
        return $this->hasOne(Empleado::class);
    }

    public function bitacoras()
    {
        return $this->hasMany(Bitacora::class);
    }
}
