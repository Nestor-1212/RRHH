@extends('layouts.app')
@section('title', 'Vacantes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0 text-white">Vacantes</h5>
        <small class="text-muted">Gestión de puestos disponibles</small>
    </div>
    <a href="{{ route('vacantes.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Nueva Vacante</a>
</div>

<div class="card mb-3"><div class="card-body py-2">
    <form class="row g-2" method="GET">
        <div class="col-md-5"><input type="text" name="buscar" class="form-control form-control-sm" placeholder="Buscar por puesto..." value="{{ request('buscar') }}"></div>
        <div class="col-md-3">
            <select name="estado" class="form-select form-select-sm">
                <option value="">— Todos —</option>
                <option value="disponible" {{ request('estado')=='disponible'?'selected':'' }}>Disponible</option>
                <option value="en_proceso" {{ request('estado')=='en_proceso'?'selected':'' }}>En Proceso</option>
                <option value="cerrada" {{ request('estado')=='cerrada'?'selected':'' }}>Cerrada</option>
            </select>
        </div>
        <div class="col-auto">
            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>
            <a href="{{ route('vacantes.index') }}" class="btn btn-sm btn-outline-secondary ms-1"><i class="bi bi-x"></i></a>
        </div>
    </form>
</div></div>

<div class="row g-3">
    @forelse($vacantes as $v)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="fw-bold text-white mb-0">{{ $v->nombre_puesto }}</h6>
                    <span class="badge bg-{{ $v->estado_badge }}">{{ ucfirst(str_replace('_',' ',$v->estado)) }}</span>
                </div>
                <div class="text-muted small mb-2"><i class="bi bi-diagram-3 me-1"></i>{{ $v->departamento->nombre }}</div>
                @if($v->salario_ofrecido)<div class="text-success small mb-1"><i class="bi bi-currency-dollar me-1"></i>${{ number_format($v->salario_ofrecido,2) }}</div>@endif
                <div class="text-muted small"><i class="bi bi-file-text me-1"></i>{{ ucfirst(str_replace('_',' ',$v->tipo_contrato)) }}</div>
                <div class="text-muted small mt-1"><i class="bi bi-calendar me-1"></i>{{ $v->fecha_apertura->format('d/m/Y') }}</div>
                @if($v->descripcion)
                <p class="text-muted mt-2 mb-0" style="font-size:.78rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">{{ $v->descripcion }}</p>
                @endif
            </div>
            <div class="card-footer d-flex gap-2 py-2">
                <a href="{{ route('vacantes.show', $v) }}" class="btn btn-sm btn-outline-info flex-fill"><i class="bi bi-eye"></i></a>
                <a href="{{ route('vacantes.edit', $v) }}" class="btn btn-sm btn-outline-warning flex-fill"><i class="bi bi-pencil"></i></a>
                <form action="{{ route('vacantes.destroy', $v) }}" method="POST" onsubmit="return confirm('¿Eliminar?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5 text-muted">No hay vacantes registradas.</div>
    @endforelse
</div>
@if($vacantes->hasPages())<div class="mt-3">{{ $vacantes->links('pagination::bootstrap-5') }}</div>@endif
@endsection
