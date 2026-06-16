@extends('layouts.app')
@section('title', 'Salida de Empleado')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('salidas.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div class="flex-grow-1">
        <h5 class="fw-bold mb-0 text-white">Salida — {{ $salida->empleado->nombre_completo }}</h5>
        <small class="text-muted">{{ $salida->fecha_salida->format('d/m/Y') }} · {{ $salida->tipo_label }}</small>
    </div>
    <span class="badge bg-{{ $salida->tipo_badge }} fs-6">{{ $salida->tipo_label }}</span>
</div>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="card mb-3">
            <div class="card-header fw-semibold">Información</div>
            <div class="card-body">
                @foreach([['Empleado',$salida->empleado->nombre_completo],['Fecha Salida',$salida->fecha_salida->format('d/m/Y')],['Tipo',$salida->tipo_label],['Último Cargo',$salida->ultimo_cargo],['Último Salario','$'.number_format($salida->ultimo_salario,2)],['Registrado por',$salida->registrador?->name]] as [$l,$v])
                @if($v)
                <div class="d-flex justify-content-between py-1 border-bottom border-secondary border-opacity-25 small">
                    <span class="text-muted">{{ $l }}</span><span class="text-white">{{ $v }}</span>
                </div>
                @endif
                @endforeach
            </div>
        </div>
        @if($salida->motivo)
        <div class="card">
            <div class="card-header fw-semibold">Motivo</div>
            <div class="card-body text-muted">{{ $salida->motivo }}</div>
        </div>
        @endif
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header fw-semibold"><i class="bi bi-paperclip me-2"></i>Documentos</div>
            <div class="card-body">
                @forelse($salida->documentos as $doc)
                <div class="d-flex align-items-center gap-2 mb-2 p-2 rounded" style="background:#1e2230;border:1px solid #2a2d35">
                    <i class="bi bi-file-earmark text-info fs-5"></i>
                    <div class="flex-grow-1">
                        <div class="text-white fw-semibold" style="font-size:.85rem">{{ $doc->nombre }}</div>
                        <div class="text-muted small">{{ $doc->tipo_label }}</div>
                    </div>
                    <a href="{{ asset('storage/'.$doc->archivo) }}" target="_blank" class="btn btn-sm btn-outline-info"><i class="bi bi-download"></i></a>
                    <form action="{{ route('salidas.documentos.destroy', $doc) }}" method="POST" onsubmit="return confirm('¿Eliminar?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
                @empty
                <p class="text-muted small">Sin documentos.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
