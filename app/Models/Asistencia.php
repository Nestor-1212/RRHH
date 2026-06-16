<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $fillable = [
        'empleado_id', 'fecha', 'hora_entrada', 'hora_salida',
        'tipo', 'observaciones', 'registrado_por',
    ];

    protected $casts = ['fecha' => 'date'];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function registrador()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    public function getTipoLabelAttribute(): string
    {
        return match($this->tipo) {
            'normal'    => 'Normal',
            'tardanza'  => 'Tardanza',
            'ausencia'  => 'Ausencia',
            'permiso'   => 'Permiso',
            'vacacion'  => 'Vacación',
            'feriado'   => 'Feriado',
            default     => $this->tipo,
        };
    }

    public function getTipoBadgeAttribute(): string
    {
        return match($this->tipo) {
            'normal'    => 'success',
            'tardanza'  => 'warning',
            'ausencia'  => 'danger',
            'permiso'   => 'info',
            'vacacion'  => 'primary',
            'feriado'   => 'secondary',
            default     => 'light',
        };
    }
}
