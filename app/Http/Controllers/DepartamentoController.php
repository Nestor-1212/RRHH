<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    public function index()
    {
        $departamentos = Departamento::withCount('empleados')->orderBy('nombre')->paginate(15);
        return view('departamentos.index', compact('departamentos'));
    }

    public function create()
    {
        return view('departamentos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:100|unique:departamentos,nombre',
            'descripcion' => 'nullable|string',
        ]);

        Departamento::create($request->only('nombre', 'descripcion', 'activo'));
        return redirect()->route('departamentos.index')->with('success', 'Departamento creado correctamente.');
    }

    public function edit(Departamento $departamento)
    {
        return view('departamentos.edit', compact('departamento'));
    }

    public function update(Request $request, Departamento $departamento)
    {
        $request->validate([
            'nombre'      => 'required|string|max:100|unique:departamentos,nombre,' . $departamento->id,
            'descripcion' => 'nullable|string',
        ]);

        $departamento->update($request->only('nombre', 'descripcion', 'activo'));
        return redirect()->route('departamentos.index')->with('success', 'Departamento actualizado.');
    }

    public function destroy(Departamento $departamento)
    {
        if ($departamento->empleados()->count() > 0) {
            return back()->with('error', 'No se puede eliminar un departamento con empleados activos.');
        }
        $departamento->delete();
        return redirect()->route('departamentos.index')->with('success', 'Departamento eliminado.');
    }
}
