<?php

namespace App\Http\Controllers;

use App\Models\Vacante;
use App\Models\Departamento;
use Illuminate\Http\Request;

class VacanteController extends Controller
{
    public function index(Request $request)
    {
        $query = Vacante::with('departamento');
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->where(function($q) use ($b) {
                $q->where('nombre_puesto', 'like', "%$b%");
            });
        }
        $vacantes = $query->latest()->paginate(15)->withQueryString();
        return view('vacantes.index', compact('vacantes'));
    }

    public function create()
    {
        $departamentos = Departamento::where('activo', true)->orderBy('nombre')->get();
        return view('vacantes.create', compact('departamentos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_puesto'   => 'required|string|max:150',
            'departamento_id' => 'required|exists:departamentos,id',
            'fecha_apertura'  => 'required|date',
            'tipo_contrato'   => 'required',
        ]);

        Vacante::create($request->all());
        return redirect()->route('vacantes.index')->with('success', 'Vacante creada correctamente.');
    }

    public function show(Vacante $vacante)
    {
        $vacante->load(['departamento', 'entrevistas.candidato', 'entrevistas.entrevistador']);
        return view('vacantes.show', compact('vacante'));
    }

    public function edit(Vacante $vacante)
    {
        $departamentos = Departamento::where('activo', true)->orderBy('nombre')->get();
        return view('vacantes.edit', compact('vacante', 'departamentos'));
    }

    public function update(Request $request, Vacante $vacante)
    {
        $request->validate([
            'nombre_puesto'   => 'required|string|max:150',
            'departamento_id' => 'required|exists:departamentos,id',
            'fecha_apertura'  => 'required|date',
            'tipo_contrato'   => 'required',
        ]);

        $vacante->update($request->all());
        return redirect()->route('vacantes.show', $vacante)->with('success', 'Vacante actualizada.');
    }

    public function destroy(Vacante $vacante)
    {
        $vacante->delete();
        return redirect()->route('vacantes.index')->with('success', 'Vacante eliminada.');
    }
}
