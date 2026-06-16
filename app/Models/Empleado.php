<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use Auditable;

    protected $fillable = [
        'candidato_id', 'codigo_empleado', 'nombre', 'apellido', 'cedula',
        'fecha_nacimiento', 'foto', 'direccion', 'telefono', 'email',
        'contacto_emergencia', 'telefono_emergencia', 'fecha_ingreso',
        'cargo', 'departamento_id', 'jefe_id', 'tipo_contrato',
        'salario', 'horario', 'jornada', 'estado',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_ingreso'    => 'date',
        'salario'          => 'decimal:2',
    ];

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    public function candidato()
    {
        return $this->belongsTo(Candidato::class);
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function jefe()
    {
        return $this->belongsTo(Empleado::class, 'jefe_id');
    }

    public function subordinados()
    {
        return $this->hasMany(Empleado::class, 'jefe_id');
    }

    public function documentos()
    {
        return $this->hasMany(EmpleadoDocumento::class);
    }

    public function salarios()
    {
        return $this->hasMany(Salario::class)->orderByDesc('fecha');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }

    public function amonestaciones()
    {
        return $this->hasMany(Amonestacion::class)->orderByDesc('fecha');
    }

    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class)->orderByDesc('created_at');
    }

    public function bitacoras()
    {
        return $this->hasMany(Bitacora::class)->orderByDesc('fecha');
    }

    public function salida()
    {
        return $this->hasOne(Salida::class);
    }

    public function getEstadoBadgeAttribute(): string
    {
        return match($this->estado) {
            'activo'   => 'success',
            'inactivo' => 'warning',
            'retirado' => 'danger',
            default    => 'secondary',
        };
    }

    public function scopeActivo($query)
    {
        return $query->where('estado', 'activo');
    }

    public function scopeByDepartamento($query, int $departamentoId)
    {
        return $query->where('departamento_id', $departamentoId);
    }
}
