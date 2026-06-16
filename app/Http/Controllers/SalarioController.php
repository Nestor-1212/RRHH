<?php

namespace App\Http\Controllers;

use App\Models\Salario;
use App\Models\Empleado;
use App\Models\Bitacora;
use Illuminate\Http\Request;

class SalarioController extends Controller
{
    public function index(Empleado $empleado)
    {
        $salarios = $empleado->salarios()->with('registrador')->paginate(20);
        return view('salarios.index', compact('empleado', 'salarios'));
    }

    public function create(Empleado $empleado)
    {
        return view('salarios.create', compact('empleado'));
    }

    public function store(Request $request, Empleado $empleado)
    {
        $request->validate([
            'tipo'   => 'required|in:aumento,bonificacion,descuento,ajuste',
            'monto'  => 'required|numeric|min:0.01',
            'fecha'  => 'required|date',
            'motivo' => 'nullable|string',
        ]);

        $salarioActual = $empleado->salario;
        $tipo  = $request->tipo;
        $monto = $request->monto;

        if (in_array($tipo, ['aumento', 'bonificacion'])) {
            $salarioNuevo = $salarioActual + $monto;
        } elseif ($tipo === 'descuento') {
            $salarioNuevo = max(0, $salarioActual - $monto);
        } else {
            $salarioNuevo = $monto;
        }

        Salario::create([
            'empleado_id'      => $empleado->id,
            'registrado_por'   => auth()->id(),
            'tipo'             => $tipo,
            'monto'            => $monto,
            'salario_anterior' => $salarioActual,
            'salario_nuevo'    => $salarioNuevo,
            'fecha'            => $request->fecha,
            'motivo'           => $request->motivo,
        ]);

        $empleado->update(['salario' => $salarioNuevo]);

        if (in_array($tipo, ['aumento', 'ajuste'])) {
            Bitacora::create([
                'empleado_id' => $empleado->id,
                'fecha'       => $request->fecha,
                'tipo'        => 'aumento_salarial',
                'descripcion' => "Cambio salarial ({$tipo}): de \${$salarioActual} a \${$salarioNuevo}. Motivo: {$request->motivo}",
                'referencia_type' => Salario::class,
                'referencia_id'   => Salario::latest()->first()->id,
            ]);
        }

        return redirect()->route('empleados.show', $empleado)->with('success', 'Cambio salarial registrado correctamente.');
    }
}
