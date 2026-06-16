@extends('layouts.app')
@section('title', 'Bitácora')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('empleados.show', $empleado) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h5 class="fw-bold mb-0 text-white">Bitácora — {{ $empleado->nombre_completo }}</h5>
        <small class="text-muted">Línea de tiempo completa del colaborador</small>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="timeline">
            @forelse($bitacoras as $b)
            <div class="timeline-item">
                <div class="tl-dot" style="background:{{ $b->color }}">
                    <i class="{{ $b->icono }}" style="font-size:.65rem;color:#fff"></i>
                </div>
                <div class="tl-content">
                    <div class="d-flex justify-content-between align-items-start">
                        <span class="fw-semibold text-white" style="font-size:.88rem">
                            {{ ucfirst(str_replace('_',' ',$b->tipo)) }}
                        </span>
                        <span class="text-muted small">{{ $b->fecha->format('d/m/Y') }}</span>
                    </div>
                    <p class="text-muted mb-0 mt-1" style="font-size:.83rem">{{ $b->descripcion }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-5 text-muted">
                <i class="bi bi-clock-history fs-1 d-block mb-2"></i>
                No hay eventos en la bitácora.
            </div>
            @endforelse
        </div>
        @if($bitacoras->hasPages())<div class="mt-3">{{ $bitacoras->links('pagination::bootstrap-5') }}</div>@endif
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header fw-semibold">Empleado</div>
            <div class="card-body">
                <div class="text-center mb-3">
                    @if($empleado->foto)
                    <img src="{{ asset('storage/'.$empleado->foto) }}" class="rounded-circle" width="70" height="70" style="object-fit:cover">
                    @else
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width:70px;height:70px;background:rgba(79,142,247,.2);color:#4f8ef7;font-size:1.5rem;font-weight:700">
                        {{ strtoupper(substr($empleado->nombre,0,1).substr($empleado->apellido,0,1)) }}
                    </div>
                    @endif
                    <div class="fw-bold text-white mt-2">{{ $empleado->nombre_completo }}</div>
                    <div class="text-muted small">{{ $empleado->cargo }}</div>
                </div>
                <div class="d-flex justify-content-between small mb-1">
                    <span class="text-muted">Ingreso</span>
                    <span class="text-white">{{ $empleado->fecha_ingreso->format('d/m/Y') }}</span>
                </div>
                <div class="d-flex justify-content-between small mb-1">
                    <span class="text-muted">Departamento</span>
                    <span class="text-white">{{ $empleado->departamento->nombre }}</span>
                </div>
                <div class="d-flex justify-content-between small">
                    <span class="text-muted">Estado</span>
                    <span class="badge bg-{{ $empleado->estado_badge }}">{{ ucfirst($empleado->estado) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
