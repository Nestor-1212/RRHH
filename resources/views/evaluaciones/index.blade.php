@extends('layouts.app')
@section('title', 'Evaluaciones de Desempeño')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0 text-white">Evaluaciones de Desempeño</h5>
        <small class="text-muted">Seguimiento del rendimiento del personal</small>
    </div>
    <a href="{{ route('evaluaciones.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Nueva Evaluación</a>
</div>

<div class="card mb-3"><div class="card-body py-2">
    <form class="row g-2" method="GET">
        <div class="col-md-4">
            <select name="empleado_id" class="form-select form-select-sm">
                <option value="">— Empleado —</option>
                @foreach($empleados as $e)
                <option value="{{ $e->id }}" {{ request('empleado_id')==$e->id?'selected':'' }}>{{ $e->nombre_completo }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="calificacion" class="form-select form-select-sm">
                <option value="">— Calificación —</option>
                @foreach(['excelente','bueno','regular','deficiente'] as $c)
                <option value="{{ $c }}" {{ request('calificacion')==$c?'selected':'' }}>{{ ucfirst($c) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>
            <a href="{{ route('evaluaciones.index') }}" class="btn btn-sm btn-outline-secondary ms-1"><i class="bi bi-x"></i></a>
        </div>
    </form>
</div></div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Empleado</th><th>Período</th><th>Productividad</th><th>Responsabilidad</th><th>Trabajo Equipo</th><th>Total</th><th>Calificación</th><th class="text-end">Acciones</th></tr></thead>
            <tbody>
                @forelse($evaluaciones as $e)
                <tr>
                    <td><a href="{{ route('empleados.show', $e->empleado) }}" class="text-info fw-semibold" style="font-size:.85rem">{{ $e->empleado->nombre_completo }}</a></td>
                    <td class="text-muted small">{{ $e->periodo }}</td>
                    <td class="text-center">{{ $e->puntaje_productividad }}/20</td>
                    <td class="text-center">{{ $e->puntaje_responsabilidad }}/20</td>
                    <td class="text-center">{{ $e->puntaje_trabajo_equipo }}/20</td>
                    <td><span class="fw-bold text-white">{{ $e->puntaje_total }}</span><span class="text-muted">/100</span></td>
                    <td><span class="badge bg-{{ $e->calificacion_badge }}">{{ ucfirst($e->calificacion) }}</span></td>
                    <td class="text-end">
                        <a href="{{ route('evaluaciones.show', $e) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('evaluaciones.edit', $e) }}" class="btn btn-sm btn-outline-warning ms-1"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('evaluaciones.destroy', $e) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('¿Eliminar?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">Sin evaluaciones registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($evaluaciones->hasPages())<div class="card-footer py-2">{{ $evaluaciones->links('pagination::bootstrap-5') }}</div>@endif
</div>
@endsection
