<?php

namespace App\Http\Controllers;

use App\Models\Salida;
use App\Models\SalidaDocumento;
use App\Models\Empleado;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SalidaController extends Controller
{
    public function index(Request $request)
    {
        $query = Salida::with('empleado');
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        $salidas = $query->latest()->paginate(15)->withQueryString();
        return view('salidas.index', compact('salidas'));
    }

    public function create(Request $request)
    {
        $empleados  = Empleado::where('estado', 'activo')->orderBy('nombre')->get();
        $empleadoId = $request->empleado_id;
        return view('salidas.create', compact('empleados', 'empleadoId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'empleado_id'   => 'required|exists:empleados,id',
            'fecha_salida'  => 'required|date',
            'tipo'          => 'required',
            'ultimo_cargo'  => 'required|string|max:150',
            'ultimo_salario' => 'required|numeric|min:0',
            'motivo'        => 'nullable|string',
            'archivos.*'    => 'nullable|file|max:5120',
        ]);

        $empleado = Empleado::findOrFail($request->empleado_id);

        $salida = Salida::create(array_merge($request->only(
            'empleado_id', 'fecha_salida', 'tipo', 'ultimo_cargo', 'ultimo_salario', 'motivo'
        ), ['registrado_por' => auth()->id()]));

        if ($request->hasFile('archivos')) {
            foreach ($request->file('archivos') as $i => $archivo) {
                $path = $archivo->store("salidas/{$salida->id}", 'public');
                $salida->documentos()->create([
                    'tipo'    => $request->tipo_archivo[$i] ?? 'otro',
                    'nombre'  => $archivo->getClientOriginalName(),
                    'archivo' => $path,
                ]);
            }
        }

        $empleado->update(['estado' => 'retirado']);

        Bitacora::create([
            'empleado_id'     => $empleado->id,
            'fecha'           => $request->fecha_salida,
            'tipo'            => 'salida',
            'descripcion'     => "Salida del empleado — Tipo: {$salida->tipo_label}. Motivo: {$request->motivo}",
            'referencia_type' => Salida::class,
            'referencia_id'   => $salida->id,
        ]);

        return redirect()->route('salidas.show', $salida)->with('success', 'Salida registrada. Empleado marcado como retirado.');
    }

    public function show(Salida $salida)
    {
        $salida->load(['empleado', 'documentos', 'registrador']);
        return view('salidas.show', compact('salida'));
    }

    public function destroyDocumento(SalidaDocumento $documento)
    {
        Storage::disk('public')->delete($documento->archivo);
        $documento->delete();
        return back()->with('success', 'Documento eliminado.');
    }
}
