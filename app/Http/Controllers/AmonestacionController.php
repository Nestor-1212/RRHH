<?php

namespace App\Http\Controllers;

use App\Models\Amonestacion;
use App\Models\AmonestacionArchivo;
use App\Models\Empleado;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AmonestacionController extends Controller
{
    public function index(Request $request)
    {
        $query = Amonestacion::with('empleado');
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        if ($request->filled('empleado_id')) {
            $query->where('empleado_id', $request->empleado_id);
        }
        $amonestaciones = $query->latest()->paginate(15)->withQueryString();
        $empleados      = Empleado::where('estado', 'activo')->orderBy('nombre')->get();
        return view('amonestaciones.index', compact('amonestaciones', 'empleados'));
    }

    public function create(Request $request)
    {
        $empleados   = Empleado::where('estado', 'activo')->orderBy('nombre')->get();
        $empleadoId  = $request->empleado_id;
        return view('amonestaciones.create', compact('empleados', 'empleadoId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'empleado_id'    => 'required|exists:empleados,id',
            'fecha'          => 'required|date',
            'tipo'           => 'required',
            'motivo'         => 'required|string',
            'archivos.*'     => 'nullable|file|max:5120',
            'tipo_archivo.*' => 'nullable|string',
        ]);

        $amonestacion = Amonestacion::create(array_merge(
            $request->only('empleado_id', 'fecha', 'tipo', 'motivo', 'descripcion', 'dias_suspension'),
            ['registrado_por' => auth()->id()]
        ));

        if ($request->hasFile('archivos')) {
            foreach ($request->file('archivos') as $i => $archivo) {
                $path = $archivo->store("amonestaciones/{$amonestacion->id}", 'public');
                $amonestacion->archivos()->create([
                    'tipo'    => $request->tipo_archivo[$i] ?? 'otro',
                    'nombre'  => $archivo->getClientOriginalName(),
                    'archivo' => $path,
                ]);
            }
        }

        $empleado = Empleado::find($request->empleado_id);
        Bitacora::create([
            'empleado_id'     => $empleado->id,
            'fecha'           => $request->fecha,
            'tipo'            => 'amonestacion',
            'descripcion'     => "{$amonestacion->tipo_label}: {$request->motivo}",
            'referencia_type' => Amonestacion::class,
            'referencia_id'   => $amonestacion->id,
        ]);

        return redirect()->route('amonestaciones.show', $amonestacion)->with('success', 'Amonestación registrada.');
    }

    public function show(Amonestacion $amonestacion)
    {
        $amonestacion->load(['empleado', 'archivos', 'registrador']);
        return view('amonestaciones.show', compact('amonestacion'));
    }

    public function edit(Amonestacion $amonestacion)
    {
        $empleados = Empleado::where('estado', 'activo')->orderBy('nombre')->get();
        return view('amonestaciones.edit', compact('amonestacion', 'empleados'));
    }

    public function update(Request $request, Amonestacion $amonestacion)
    {
        $request->validate([
            'fecha'  => 'required|date',
            'tipo'   => 'required',
            'motivo' => 'required|string',
        ]);

        $amonestacion->update($request->only('fecha', 'tipo', 'motivo', 'descripcion', 'dias_suspension'));

        if ($request->hasFile('archivos')) {
            foreach ($request->file('archivos') as $i => $archivo) {
                $path = $archivo->store("amonestaciones/{$amonestacion->id}", 'public');
                $amonestacion->archivos()->create([
                    'tipo'    => $request->tipo_archivo[$i] ?? 'otro',
                    'nombre'  => $archivo->getClientOriginalName(),
                    'archivo' => $path,
                ]);
            }
        }

        return redirect()->route('amonestaciones.show', $amonestacion)->with('success', 'Amonestación actualizada.');
    }

    public function destroyArchivo(AmonestacionArchivo $archivo)
    {
        Storage::disk('public')->delete($archivo->archivo);
        $archivo->delete();
        return back()->with('success', 'Archivo eliminado.');
    }

    public function destroy(Amonestacion $amonestacion)
    {
        foreach ($amonestacion->archivos as $archivo) {
            Storage::disk('public')->delete($archivo->archivo);
        }
        $amonestacion->delete();
        return redirect()->route('amonestaciones.index')->with('success', 'Amonestación eliminada.');
    }
}
