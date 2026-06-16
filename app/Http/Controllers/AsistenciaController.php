<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    public function index(Request $request)
    {
        $empleados = Empleado::where('estado', 'activo')->orderBy('nombre')->get();
        $empleadoId = $request->empleado_id;
        $mes  = $request->mes ?? Carbon::now()->month;
        $anio = $request->anio ?? Carbon::now()->year;

        $query = Asistencia::with('empleado')->whereMonth('fecha', $mes)->whereYear('fecha', $anio);
        if ($empleadoId) {
            $query->where('empleado_id', $empleadoId);
        }
        $asistencias = $query->orderBy('fecha', 'desc')->paginate(30)->withQueryString();

        return view('asistencias.index', compact('asistencias', 'empleados', 'empleadoId', 'mes', 'anio'));
    }

    public function create()
    {
        $empleados = Empleado::where('estado', 'activo')->orderBy('nombre')->get();
        return view('asistencias.create', compact('empleados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'empleado_id'  => 'required|exists:empleados,id',
            'fecha'        => 'required|date',
            'tipo'         => 'required',
            'hora_entrada' => 'nullable|date_format:H:i',
            'hora_salida'  => 'nullable|date_format:H:i',
        ]);

        Asistencia::updateOrCreate(
            ['empleado_id' => $request->empleado_id, 'fecha' => $request->fecha],
            array_merge($request->only('empleado_id', 'fecha', 'hora_entrada', 'hora_salida', 'tipo', 'observaciones'), [
                'registrado_por' => auth()->id(),
            ])
        );

        return redirect()->route('asistencias.index')->with('success', 'Asistencia registrada.');
    }

    public function edit(Asistencia $asistencia)
    {
        $empleados = Empleado::where('estado', 'activo')->orderBy('nombre')->get();
        return view('asistencias.edit', compact('asistencia', 'empleados'));
    }

    public function update(Request $request, Asistencia $asistencia)
    {
        $request->validate([
            'tipo'         => 'required',
            'hora_entrada' => 'nullable|date_format:H:i',
            'hora_salida'  => 'nullable|date_format:H:i',
        ]);

        $asistencia->update($request->only('hora_entrada', 'hora_salida', 'tipo', 'observaciones'));
        return redirect()->route('asistencias.index')->with('success', 'Asistencia actualizada.');
    }

    public function destroy(Asistencia $asistencia)
    {
        $asistencia->delete();
        return back()->with('success', 'Registro eliminado.');
    }
}
