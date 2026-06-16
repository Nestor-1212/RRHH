<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Amonestacion extends Model
{
    protected $table = 'amonestaciones';

    protected $fillable = [
        'empleado_id', 'registrado_por', 'fecha', 'tipo',
        'motivo', 'descripcion', 'dias_suspension',
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

    public function archivos()
    {
        return $this->hasMany(AmonestacionArchivo::class);
    }

    public function getTipoLabelAttribute(): string
    {
        return match($this->tipo) {
            'llamado_atencion' => 'Llamado de Atención',
            'verbal'           => 'Amonestación Verbal',
            'escrita'          => 'Amonestación Escrita',
            'suspension'       => 'Suspensión',
            default            => $this->tipo,
        };
    }

    public function getTipoBadgeAttribute(): string
    {
        return match($this->tipo) {
            'llamado_atencion' => 'info',
            'verbal'           => 'warning',
            'escrita'          => 'orange',
            'suspension'       => 'danger',
            default            => 'secondary',
        };
    }
}
