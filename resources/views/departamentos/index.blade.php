@extends('layouts.app')
@section('title', 'Departamentos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0 text-white">Departamentos</h5>
    <a href="{{ route('departamentos.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Nuevo</a>
</div>
<div class="row g-3">
    @forelse($departamentos as $d)
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="fw-bold text-white mb-1">{{ $d->nombre }}</h6>
                        <p class="text-muted small mb-1">{{ $d->descripcion ?? 'Sin descripción' }}</p>
                        <span class="badge bg-info">{{ $d->empleados_count }} empleado(s)</span>
                    </div>
                    <span class="badge bg-{{ $d->activo?'success':'secondary' }}">{{ $d->activo?'Activo':'Inactivo' }}</span>
                </div>
            </div>
            <div class="card-footer d-flex gap-2 py-2">
                <a href="{{ route('departamentos.edit', $d) }}" class="btn btn-sm btn-outline-warning flex-fill"><i class="bi bi-pencil"></i></a>
                <form action="{{ route('departamentos.destroy', $d) }}" method="POST" class="flex-fill" onsubmit="return confirm('¿Eliminar?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger w-100"><i class="bi bi-trash"></i></button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5 text-muted">Sin departamentos registrados.</div>
    @endforelse
</div>
@if($departamentos->hasPages())<div class="mt-3">{{ $departamentos->links('pagination::bootstrap-5') }}</div>@endif
@endsection
