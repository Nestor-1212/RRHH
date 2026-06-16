<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalidaDocumento extends Model
{
    protected $table = 'salida_documentos';

    protected $fillable = ['salida_id', 'tipo', 'nombre', 'archivo'];

    public function salida()
    {
        return $this->belongsTo(Salida::class);
    }

    public function getTipoLabelAttribute(): string
    {
        return match($this->tipo) {
            'carta_renuncia' => 'Carta de Renuncia',
            'carta_despido'  => 'Carta de Despido',
            'acta_entrega'   => 'Acta de Entrega',
            'liquidacion'    => 'Liquidación',
            'otro'           => 'Otro',
            default          => $this->tipo,
        };
    }
}
