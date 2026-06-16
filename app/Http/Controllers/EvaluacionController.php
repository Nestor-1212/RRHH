<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\Empleado;
use App\Models\Bitacora;
use App\Models\User;
use Illuminate\Http\Request;

class EvaluacionController extends Controller
{
    public function index(Request $request)
    {
        $query = Evaluacion::with(['empleado', 'evaluador']);
        if ($request->filled('calificacion')) {
            $query->where('calificacion', $request->calificacion);
        }
        if ($request->filled('empleado_id')) {
            $query->where('empleado_id', $request->empleado_id);
        }
        $evaluaciones = $query->latest()->paginate(15)->withQueryString();
        $empleados    = Empleado::where('estado', 'activo')->orderBy('nombre')->get();
        return view('evaluaciones.index', compact('evaluaciones', 'empleados'));
    }

    public function create(Request $request)
    {
        $empleados = Empleado::where('estado', 'activo')->orderBy('nombre')->get();
        $empleadoId = $request->empleado_id;
        return view('evaluaciones.create', compact('empleados', 'empleadoId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'empleado_id'           => 'required|exists:empleados,id',
            'periodo'               => 'required|string|max:100',
            'puntaje_productividad' => 'required|integer|min:1|max:20',
            'puntaje_responsabilidad' => 'required|integer|min:1|max:20',
            'puntaje_trabajo_equipo' => 'required|integer|min:1|max:20',
            'puntaje_calidad'       => 'required|integer|min:1|max:20',
            'puntaje_cumplimiento'  => 'required|integer|min:1|max:20',
        ]);

        $total = $request->puntaje_productividad
            + $request->puntaje_responsabilidad
            + $request->puntaje_trabajo_equipo
            + $request->puntaje_calidad
            + $request->puntaje_cumplimiento;

        $calificacion = match(true) {
            $total >= 90 => 'excelente',
            $total >= 70 => 'bueno',
            $total >= 50 => 'regular',
            default      => 'deficiente',
        };

        $evaluacion = Evaluacion::create(array_merge($request->only(
            'empleado_id', 'periodo', 'puntaje_productividad', 'puntaje_responsabilidad',
            'puntaje_trabajo_equipo', 'puntaje_calidad', 'puntaje_cumplimiento', 'comentarios'
        ), [
            'evaluador_id'  => auth()->id(),
            'puntaje_total' => $total,
            'calificacion'  => $calificacion,
        ]));

        Bitacora::create([
            'empleado_id'     => $request->empleado_id,
            'fecha'           => now()->toDateString(),
            'tipo'            => 'evaluacion',
            'descripcion'     => "Evaluación de desempeño — Periodo: {$request->periodo}. Calificación: {$calificacion} ({$total}/100).",
            'referencia_type' => Evaluacion::class,
            'referencia_id'   => $evaluacion->id,
        ]);

        return redirect()->route('evaluaciones.show', $evaluacion)->with('success', 'Evaluación registrada correctamente.');
    }

    public function show(Evaluacion $evaluacion)
    {
        $evaluacion->load(['empleado', 'evaluador']);
        return view('evaluaciones.show', compact('evaluacion'));
    }

    public function edit(Evaluacion $evaluacion)
    {
        $empleados = Empleado::where('estado', 'activo')->orderBy('nombre')->get();
        return view('evaluaciones.edit', compact('evaluacion', 'empleados'));
    }

    public function update(Request $request, Evaluacion $evaluacion)
    {
        $request->validate([
            'periodo'               => 'required|string|max:100',
            'puntaje_productividad' => 'required|integer|min:1|max:20',
            'puntaje_responsabilidad' => 'required|integer|min:1|max:20',
            'puntaje_trabajo_equipo' => 'required|integer|min:1|max:20',
            'puntaje_calidad'       => 'required|integer|min:1|max:20',
            'puntaje_cumplimiento'  => 'required|integer|min:1|max:20',
        ]);

        $total = $request->puntaje_productividad
            + $request->puntaje_responsabilidad
            + $request->puntaje_trabajo_equipo
            + $request->puntaje_calidad
            + $request->puntaje_cumplimiento;

        $calificacion = match(true) {
            $total >= 90 => 'excelente',
            $total >= 70 => 'bueno',
            $total >= 50 => 'regular',
            default      => 'deficiente',
        };

        $evaluacion->update(array_merge($request->only(
            'periodo', 'puntaje_productividad', 'puntaje_responsabilidad',
            'puntaje_trabajo_equipo', 'puntaje_calidad', 'puntaje_cumplimiento', 'comentarios'
        ), ['puntaje_total' => $total, 'calificacion' => $calificacion]));

        return redirect()->route('evaluaciones.show', $evaluacion)->with('success', 'Evaluación actualizada.');
    }

    public function destroy(Evaluacion $evaluacion)
    {
        $evaluacion->delete();
        return redirect()->route('evaluaciones.index')->with('success', 'Evaluación eliminada.');
    }
}
