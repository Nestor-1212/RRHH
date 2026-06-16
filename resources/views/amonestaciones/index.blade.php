@extends('layouts.app')
@section('title', 'Amonestaciones')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0 text-white">Amonestaciones y Disciplina</h5>
        <small class="text-muted">Registro de incidencias y medidas disciplinarias</small>
    </div>
    <a href="{{ route('amonestaciones.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Nueva</a>
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
            <select name="tipo" class="form-select form-select-sm">
                <option value="">— Tipo —</option>
                @foreach(['llamado_atencion'=>'Llamado de Atención','verbal'=>'Verbal','escrita'=>'Escrita','suspension'=>'Suspensión'] as $k=>$v)
                <option value="{{ $k }}" {{ request('tipo')==$k?'selected':'' }}>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>
            <a href="{{ route('amonestaciones.index') }}" class="btn btn-sm btn-outline-secondary ms-1"><i class="bi bi-x"></i></a>
        </div>
    </form>
</div></div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Empleado</th><th>Fecha</th><th>Tipo</th><th>Motivo</th><th>Archivos</th><th class="text-end">Acciones</th></tr></thead>
            <tbody>
                @forelse($amonestaciones as $a)
                <tr>
                    <td>
                        <a href="{{ route('empleados.show', $a->empleado) }}" class="text-info fw-semibold" style="font-size:.85rem">{{ $a->empleado->nombre_completo }}</a>
                    </td>
                    <td class="text-muted small">{{ $a->fecha->format('d/m/Y') }}</td>
                    <td><span class="badge bg-{{ $a->tipo_badge }}">{{ $a->tipo_label }}</span></td>
                    <td class="text-muted small" style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $a->motivo }}</td>
                    <td class="text-muted small">{{ $a->archivos()->count() }} archivo(s)</td>
                    <td class="text-end">
                        <a href="{{ route('amonestaciones.show', $a) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('amonestaciones.edit', $a) }}" class="btn btn-sm btn-outline-warning ms-1"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('amonestaciones.destroy', $a) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('¿Eliminar?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">Sin amonestaciones registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($amonestaciones->hasPages())<div class="card-footer py-2">{{ $amonestaciones->links('pagination::bootstrap-5') }}</div>@endif
</div>
@endsection
