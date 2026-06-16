<?php

namespace App\Http\Controllers;

use App\Models\Candidato;
use App\Models\CandidatoEstudio;
use App\Models\CandidatoExperiencia;
use App\Models\CandidatoReferencia;
use App\Models\CandidatoIdioma;
use App\Models\CandidatoHabilidad;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidatoController extends Controller
{
    public function index(Request $request)
    {
        $query = Candidato::query();
        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->where(function($q) use ($b) {
                $q->where('nombre', 'like', "%$b%")
                  ->orWhere('apellido', 'like', "%$b%")
                  ->orWhere('cedula', 'like', "%$b%")
                  ->orWhere('email', 'like', "%$b%");
            });
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        $candidatos = $query->latest()->paginate(15)->withQueryString();
        return view('candidatos.index', compact('candidatos'));
    }

    public function create()
    {
        return view('candidatos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'     => 'required|string|max:100',
            'apellido'   => 'required|string|max:100',
            'cedula'     => 'required|string|max:30|unique:candidatos,cedula',
            'email'      => 'nullable|email|max:150',
            'telefono'   => 'nullable|string|max:20',
            'foto'       => 'nullable|image|max:2048',
            'hoja_vida'  => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $data = $request->except(['foto', 'hoja_vida', '_token']);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('candidatos/fotos', 'public');
        }
        if ($request->hasFile('hoja_vida')) {
            $data['hoja_vida'] = $request->file('hoja_vida')->store('candidatos/hojas_vida', 'public');
        }

        $candidato = Candidato::create($data);

        Bitacora::create([
            'candidato_id'   => $candidato->id,
            'fecha'          => now()->toDateString(),
            'tipo'           => 'candidatura',
            'descripcion'    => "Candidato registrado en el sistema.",
            'referencia_type' => Candidato::class,
            'referencia_id'  => $candidato->id,
        ]);

        return redirect()->route('candidatos.show', $candidato)->with('success', 'Candidato registrado correctamente.');
    }

    public function show(Candidato $candidato)
    {
        $candidato->load(['estudios', 'experiencias', 'referencias', 'idiomas', 'habilidades', 'entrevistas.vacante', 'bitacoras']);
        return view('candidatos.show', compact('candidato'));
    }

    public function edit(Candidato $candidato)
    {
        return view('candidatos.edit', compact('candidato'));
    }

    public function update(Request $request, Candidato $candidato)
    {
        $request->validate([
            'nombre'    => 'required|string|max:100',
            'apellido'  => 'required|string|max:100',
            'cedula'    => 'required|string|max:30|unique:candidatos,cedula,' . $candidato->id,
            'email'     => 'nullable|email|max:150',
            'telefono'  => 'nullable|string|max:20',
            'foto'      => 'nullable|image|max:2048',
            'hoja_vida' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $data = $request->except(['foto', 'hoja_vida', '_token', '_method']);

        if ($request->hasFile('foto')) {
            if ($candidato->foto) Storage::disk('public')->delete($candidato->foto);
            $data['foto'] = $request->file('foto')->store('candidatos/fotos', 'public');
        }
        if ($request->hasFile('hoja_vida')) {
            if ($candidato->hoja_vida) Storage::disk('public')->delete($candidato->hoja_vida);
            $data['hoja_vida'] = $request->file('hoja_vida')->store('candidatos/hojas_vida', 'public');
        }

        $candidato->update($data);
        return redirect()->route('candidatos.show', $candidato)->with('success', 'Candidato actualizado.');
    }

    public function destroy(Candidato $candidato)
    {
        if ($candidato->foto) Storage::disk('public')->delete($candidato->foto);
        if ($candidato->hoja_vida) Storage::disk('public')->delete($candidato->hoja_vida);
        $candidato->delete();
        return redirect()->route('candidatos.index')->with('success', 'Candidato eliminado.');
    }

    public function storeEstudio(Request $request, Candidato $candidato)
    {
        $request->validate([
            'nivel'       => 'required',
            'institucion' => 'required|string|max:200',
            'carrera'     => 'nullable|string|max:200',
        ]);
        $candidato->estudios()->create($request->only('nivel', 'institucion', 'carrera', 'anio_inicio', 'anio_fin', 'graduado'));
        return back()->with('success', 'Estudio agregado.');
    }

    public function destroyEstudio(CandidatoEstudio $estudio)
    {
        $estudio->delete();
        return back()->with('success', 'Estudio eliminado.');
    }

    public function storeExperiencia(Request $request, Candidato $candidato)
    {
        $request->validate([
            'empresa'      => 'required|string|max:200',
            'cargo'        => 'required|string|max:200',
            'fecha_inicio' => 'required|date',
        ]);
        $candidato->experiencias()->create($request->only('empresa', 'cargo', 'fecha_inicio', 'fecha_fin', 'actualmente', 'descripcion'));
        return back()->with('success', 'Experiencia agregada.');
    }

    public function destroyExperiencia(CandidatoExperiencia $experiencia)
    {
        $experiencia->delete();
        return back()->with('success', 'Experiencia eliminada.');
    }

    public function storeReferencia(Request $request, Candidato $candidato)
    {
        $request->validate([
            'nombre'   => 'required|string|max:150',
            'relacion' => 'required|string|max:100',
        ]);
        $candidato->referencias()->create($request->only('nombre', 'relacion', 'telefono', 'email'));
        return back()->with('success', 'Referencia agregada.');
    }

    public function destroyReferencia(CandidatoReferencia $referencia)
    {
        $referencia->delete();
        return back()->with('success', 'Referencia eliminada.');
    }

    public function storeIdioma(Request $request, Candidato $candidato)
    {
        $request->validate(['idioma' => 'required|string|max:100', 'nivel' => 'required']);
        $candidato->idiomas()->create($request->only('idioma', 'nivel'));
        return back()->with('success', 'Idioma agregado.');
    }

    public function destroyIdioma(CandidatoIdioma $idioma)
    {
        $idioma->delete();
        return back()->with('success', 'Idioma eliminado.');
    }

    public function storeHabilidad(Request $request, Candidato $candidato)
    {
        $request->validate(['habilidad' => 'required|string|max:150']);
        $candidato->habilidades()->create($request->only('habilidad', 'nivel'));
        return back()->with('success', 'Habilidad agregada.');
    }

    public function destroyHabilidad(CandidatoHabilidad $habilidad)
    {
        $habilidad->delete();
        return back()->with('success', 'Habilidad eliminada.');
    }
}
