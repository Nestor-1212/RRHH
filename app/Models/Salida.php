<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salida extends Model
{
    protected $fillable = [
        'empleado_id', 'registrado_por', 'fecha_salida', 'tipo',
        'ultimo_cargo', 'ultimo_salario', 'motivo',
    ];

    protected $casts = [
        'fecha_salida'  => 'date',
        'ultimo_salario' => 'decimal:2',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function registrador()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    public function documentos()
    {
        return $this->hasMany(SalidaDocumento::class);
    }

    public function getTipoLabelAttribute(): string
    {
        return match($this->tipo) {
            'renuncia'             => 'Renuncia',
            'despido'              => 'Despido',
            'finalizacion_contrato' => 'Finalización de Contrato',
            'jubilacion'           => 'Jubilación',
            'otro'                 => 'Otro',
            default                => $this->tipo,
        };
    }

    public function getTipoBadgeAttribute(): string
    {
        return match($this->tipo) {
            'renuncia'              => 'info',
            'despido'               => 'danger',
            'finalizacion_contrato' => 'warning',
            'jubilacion'            => 'primary',
            'otro'                  => 'secondary',
            default                 => 'secondary',
        };
    }
}
