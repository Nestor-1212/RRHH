<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacante extends Model
{
    protected $fillable = [
        'nombre_puesto', 'departamento_id', 'descripcion', 'requisitos',
        'salario_ofrecido', 'tipo_contrato', 'fecha_apertura', 'fecha_cierre',
        'estado', 'notas',
    ];

    protected $casts = [
        'fecha_apertura' => 'date',
        'fecha_cierre'   => 'date',
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function entrevistas()
    {
        return $this->hasMany(Entrevista::class);
    }

    public function getEstadoBadgeAttribute(): string
    {
        return match($this->estado) {
            'disponible' => 'success',
            'en_proceso' => 'warning',
            'cerrada'    => 'danger',
            default      => 'secondary',
        };
    }
}
