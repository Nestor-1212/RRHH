@extends('layouts.app')
@section('title', 'Asistencias')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0 text-white">Control de Asistencias</h5>
        <small class="text-muted">Registro de entradas, salidas y ausencias</small>
    </div>
    <a href="{{ route('asistencias.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Registrar</a>
</div>

<div class="card mb-3"><div class="card-body py-2">
    <form class="row g-2 align-items-end" method="GET">
        <div class="col-md-4">
            <select name="empleado_id" class="form-select form-select-sm">
                <option value="">— Todos los empleados —</option>
                @foreach($empleados as $e)
                <option value="{{ $e->id }}" {{ request('empleado_id')==$e->id?'selected':'' }}>{{ $e->nombre_completo }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="mes" class="form-select form-select-sm">
                @for($m=1;$m<=12;$m++)
                <option value="{{ $m }}" {{ $mes==$m?'selected':'' }}>{{ Carbon\Carbon::create(null,$m)->translatedFormat('F') }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-2">
            <select name="anio" class="form-select form-select-sm">
                @for($y=now()->year;$y>=2020;$y--)
                <option value="{{ $y }}" {{ $anio==$y?'selected':'' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div class="col-auto">
            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>
            <a href="{{ route('asistencias.index') }}" class="btn btn-sm btn-outline-secondary ms-1"><i class="bi bi-x"></i></a>
        </div>
    </form>
</div></div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Empleado</th><th>Fecha</th><th>Entrada</th><th>Salida</th><th>Tipo</th><th>Observaciones</th><th class="text-end">Acciones</th></tr></thead>
            <tbody>
                @forelse($asistencias as $a)
                <tr>
                    <td class="fw-semibold text-white" style="font-size:.85rem">{{ $a->empleado->nombre_completo }}</td>
                    <td class="text-muted small">{{ $a->fecha->format('d/m/Y') }}</td>
                    <td class="text-success">{{ $a->hora_entrada ?? '—' }}</td>
                    <td class="text-muted">{{ $a->hora_salida ?? '—' }}</td>
                    <td><span class="badge bg-{{ $a->tipo_badge }}">{{ $a->tipo_label }}</span></td>
                    <td class="text-muted small">{{ $a->observaciones ?? '—' }}</td>
                    <td class="text-end">
                        <a href="{{ route('asistencias.edit', $a) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('asistencias.destroy', $a) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('¿Eliminar?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">No hay registros para este período.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($asistencias->hasPages())<div class="card-footer py-2">{{ $asistencias->links('pagination::bootstrap-5') }}</div>@endif
</div>
@endsection
