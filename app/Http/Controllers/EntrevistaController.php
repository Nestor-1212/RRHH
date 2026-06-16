<?php

namespace App\Http\Controllers;

use App\Models\Entrevista;
use App\Models\Candidato;
use App\Models\Vacante;
use App\Models\Bitacora;
use App\Models\User;
use Illuminate\Http\Request;

class EntrevistaController extends Controller
{
    public function index(Request $request)
    {
        $query = Entrevista::with(['candidato', 'vacante', 'entrevistador']);
        if ($request->filled('resultado')) {
            $query->where('resultado', $request->resultado);
        }
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        $entrevistas = $query->latest()->paginate(15)->withQueryString();
        return view('entrevistas.index', compact('entrevistas'));
    }

    public function create(Request $request)
    {
        $candidatos  = Candidato::whereIn('estado', ['activo', 'en_proceso'])->orderBy('nombre')->get();
        $vacantes    = Vacante::whereIn('estado', ['disponible', 'en_proceso'])->orderBy('nombre_puesto')->get();
        $entrevistadores = User::orderBy('name')->get();
        $candidatoId = $request->candidato_id;
        return view('entrevistas.create', compact('candidatos', 'vacantes', 'entrevistadores', 'candidatoId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'candidato_id'     => 'required|exists:candidatos,id',
            'vacante_id'       => 'required|exists:vacantes,id',
            'entrevistador_id' => 'required|exists:users,id',
            'fecha_entrevista' => 'required|date',
            'tipo'             => 'required|in:inicial,tecnica,final',
            'puntaje_experiencia'    => 'required|integer|min:1|max:10',
            'puntaje_conocimiento'   => 'required|integer|min:1|max:10',
            'puntaje_comunicacion'   => 'required|integer|min:1|max:10',
            'puntaje_actitud'        => 'required|integer|min:1|max:10',
            'puntaje_disponibilidad' => 'required|integer|min:1|max:10',
            'resultado'        => 'required|in:pendiente,seleccionado,no_seleccionado',
        ]);

        $data = $request->all();
        $data['puntaje_total'] = $data['puntaje_experiencia']
            + $data['puntaje_conocimiento']
            + $data['puntaje_comunicacion']
            + $data['puntaje_actitud']
            + $data['puntaje_disponibilidad'];

        $entrevista = Entrevista::create($data);

        $candidato = Candidato::find($data['candidato_id']);
        if ($data['resultado'] === 'seleccionado') {
            $candidato->update(['estado' => 'en_proceso']);
        } elseif ($data['resultado'] === 'no_seleccionado') {
            $candidato->update(['estado' => 'descartado']);
        }

        Bitacora::create([
            'candidato_id'    => $candidato->id,
            'fecha'           => now()->toDateString(),
            'tipo'            => 'entrevista',
            'descripcion'     => "Entrevista {$entrevista->tipo_label} realizada. Resultado: {$entrevista->resultado}. Puntaje: {$entrevista->puntaje_total}/50.",
            'referencia_type' => Entrevista::class,
            'referencia_id'   => $entrevista->id,
        ]);

        return redirect()->route('entrevistas.show', $entrevista)->with('success', 'Entrevista registrada correctamente.');
    }

    public function show(Entrevista $entrevista)
    {
        $entrevista->load(['candidato', 'vacante', 'entrevistador']);
        return view('entrevistas.show', compact('entrevista'));
    }

    public function edit(Entrevista $entrevista)
    {
        $candidatos  = Candidato::orderBy('nombre')->get();
        $vacantes    = Vacante::orderBy('nombre_puesto')->get();
        $entrevistadores = User::orderBy('name')->get();
        return view('entrevistas.edit', compact('entrevista', 'candidatos', 'vacantes', 'entrevistadores'));
    }

    public function update(Request $request, Entrevista $entrevista)
    {
        $request->validate([
            'fecha_entrevista'       => 'required|date',
            'tipo'                   => 'required',
            'puntaje_experiencia'    => 'required|integer|min:1|max:10',
            'puntaje_conocimiento'   => 'required|integer|min:1|max:10',
            'puntaje_comunicacion'   => 'required|integer|min:1|max:10',
            'puntaje_actitud'        => 'required|integer|min:1|max:10',
            'puntaje_disponibilidad' => 'required|integer|min:1|max:10',
            'resultado'              => 'required',
        ]);

        $data = $request->all();
        $data['puntaje_total'] = $data['puntaje_experiencia']
            + $data['puntaje_conocimiento']
            + $data['puntaje_comunicacion']
            + $data['puntaje_actitud']
            + $data['puntaje_disponibilidad'];

        $entrevista->update($data);
        return redirect()->route('entrevistas.show', $entrevista)->with('success', 'Entrevista actualizada.');
    }

    public function destroy(Entrevista $entrevista)
    {
        $entrevista->delete();
        return redirect()->route('entrevistas.index')->with('success', 'Entrevista eliminada.');
    }
}
