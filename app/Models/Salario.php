<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salario extends Model
{
    protected $fillable = [
        'empleado_id', 'registrado_por', 'tipo', 'monto',
        'salario_anterior', 'salario_nuevo', 'fecha', 'motivo',
    ];

    protected $casts = [
        'fecha'            => 'date',
        'monto'            => 'decimal:2',
        'salario_anterior' => 'decimal:2',
        'salario_nuevo'    => 'decimal:2',
    ];

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
            'inicial'      => 'Salario Inicial',
            'aumento'      => 'Aumento',
            'bonificacion' => 'Bonificación',
            'descuento'    => 'Descuento',
            'ajuste'       => 'Ajuste',
            default        => $this->tipo,
        };
    }

    public function getTipoBadgeAttribute(): string
    {
        return match($this->tipo) {
            'inicial'      => 'primary',
            'aumento'      => 'success',
            'bonificacion' => 'info',
            'descuento'    => 'danger',
            'ajuste'       => 'warning',
            default        => 'secondary',
        };
    }
}
