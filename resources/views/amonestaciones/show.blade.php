@extends('layouts.app')
@section('title', 'Amonestación')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('amonestaciones.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div class="flex-grow-1">
        <h5 class="fw-bold mb-0 text-white">Amonestación — {{ $amonestacion->empleado->nombre_completo }}</h5>
        <small class="text-muted">{{ $amonestacion->fecha->format('d/m/Y') }} · {{ $amonestacion->tipo_label }}</small>
    </div>
    <span class="badge bg-{{ $amonestacion->tipo_badge }} fs-6">{{ $amonestacion->tipo_label }}</span>
    <a href="{{ route('amonestaciones.edit', $amonestacion) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
</div>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="card mb-3">
            <div class="card-header fw-semibold">Información</div>
            <div class="card-body">
                @foreach([['Empleado',$amonestacion->empleado->nombre_completo],['Fecha',$amonestacion->fecha->format('d/m/Y')],['Tipo',$amonestacion->tipo_label],['Registrado por',$amonestacion->registrador?->name],['Días suspensión',$amonestacion->dias_suspension>0?$amonestacion->dias_suspension.' días':null]] as [$l,$v])
                @if($v)
                <div class="d-flex justify-content-between py-1 border-bottom border-secondary border-opacity-25 small">
                    <span class="text-muted">{{ $l }}</span><span class="text-white">{{ $v }}</span>
                </div>
                @endif
                @endforeach
            </div>
        </div>
        <div class="card">
            <div class="card-header fw-semibold">Motivo</div>
            <div class="card-body">
                <p class="text-white mb-2">{{ $amonestacion->motivo }}</p>
                @if($amonestacion->descripcion)<p class="text-muted small mb-0">{{ $amonestacion->descripcion }}</p>@endif
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span class="fw-semibold"><i class="bi bi-paperclip me-2"></i>Archivos Adjuntos</span>
            </div>
            <div class="card-body">
                @forelse($amonestacion->archivos as $arch)
                <div class="d-flex align-items-center gap-2 mb-2 p-2 rounded" style="background:#1e2230;border:1px solid #2a2d35">
                    <i class="bi bi-file-earmark text-info fs-5"></i>
                    <div class="flex-grow-1">
                        <div class="text-white fw-semibold" style="font-size:.85rem">{{ $arch->nombre }}</div>
                        <div class="text-muted small">{{ ucfirst($arch->tipo) }}</div>
                    </div>
                    <a href="{{ asset('storage/'.$arch->archivo) }}" target="_blank" class="btn btn-sm btn-outline-info"><i class="bi bi-download"></i></a>
                    <form action="{{ route('amonestaciones.archivos.destroy', $arch) }}" method="POST" onsubmit="return confirm('¿Eliminar?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
                @empty
                <p class="text-muted small">Sin archivos adjuntos.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
