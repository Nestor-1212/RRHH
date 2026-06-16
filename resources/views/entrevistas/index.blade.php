@extends('layouts.app')
@section('title', 'Entrevistas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0 text-white">Entrevistas</h5>
        <small class="text-muted">Proceso de selección y evaluación de candidatos</small>
    </div>
    <a href="{{ route('entrevistas.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Nueva Entrevista</a>
</div>

<div class="card mb-3"><div class="card-body py-2">
    <form class="row g-2" method="GET">
        <div class="col-md-3">
            <select name="tipo" class="form-select form-select-sm">
                <option value="">— Tipo —</option>
                <option value="inicial" {{ request('tipo')=='inicial'?'selected':'' }}>Inicial</option>
                <option value="tecnica" {{ request('tipo')=='tecnica'?'selected':'' }}>Técnica</option>
                <option value="final" {{ request('tipo')=='final'?'selected':'' }}>Final</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="resultado" class="form-select form-select-sm">
                <option value="">— Resultado —</option>
                <option value="pendiente" {{ request('resultado')=='pendiente'?'selected':'' }}>Pendiente</option>
                <option value="seleccionado" {{ request('resultado')=='seleccionado'?'selected':'' }}>Seleccionado</option>
                <option value="no_seleccionado" {{ request('resultado')=='no_seleccionado'?'selected':'' }}>No Seleccionado</option>
            </select>
        </div>
        <div class="col-auto">
            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>
            <a href="{{ route('entrevistas.index') }}" class="btn btn-sm btn-outline-secondary ms-1"><i class="bi bi-x"></i></a>
        </div>
    </form>
</div></div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Candidato</th><th>Vacante</th><th>Fecha</th><th>Tipo</th><th>Puntaje</th><th>Resultado</th><th>Entrevistador</th><th class="text-end">Acciones</th></tr></thead>
            <tbody>
                @forelse($entrevistas as $e)
                <tr>
                    <td>
                        <a href="{{ route('candidatos.show', $e->candidato) }}" class="text-info fw-semibold" style="font-size:.85rem">{{ $e->candidato->nombre_completo }}</a>
                    </td>
                    <td class="text-muted small">{{ $e->vacante->nombre_puesto }}</td>
                    <td class="text-muted small">{{ $e->fecha_entrevista->format('d/m/Y H:i') }}</td>
                    <td><span class="badge bg-secondary">{{ $e->tipo_label }}</span></td>
                    <td>
                        <div class="d-flex align-items-center gap-1">
                            <div class="progress flex-grow-1" style="height:6px;width:60px">
                                <div class="progress-bar bg-{{ $e->puntaje_total>=40?'success':($e->puntaje_total>=25?'warning':'danger') }}"
                                     style="width:{{ ($e->puntaje_total/50)*100 }}%"></div>
                            </div>
                            <span class="small fw-semibold">{{ $e->puntaje_total }}/50</span>
                        </div>
                    </td>
                    <td><span class="badge bg-{{ $e->resultado_badge }}">{{ ucfirst(str_replace('_',' ',$e->resultado)) }}</span></td>
                    <td class="text-muted small">{{ $e->entrevistador->name }}</td>
                    <td class="text-end">
                        <a href="{{ route('entrevistas.show', $e) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('entrevistas.edit', $e) }}" class="btn btn-sm btn-outline-warning ms-1"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('entrevistas.destroy', $e) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('¿Eliminar?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">No hay entrevistas registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($entrevistas->hasPages())
    <div class="card-footer py-2 d-flex justify-content-between align-items-center">
        <small class="text-muted">{{ $entrevistas->total() }} entrevistas</small>
        {{ $entrevistas->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
