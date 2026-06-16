@extends('layouts.app')
@section('title', 'Editar Departamento')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('departamentos.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0 text-white">Editar Departamento — {{ $departamento->nombre }}</h5>
</div>
<div class="row"><div class="col-md-5">
<form action="{{ route('departamentos.update', $departamento) }}" method="POST">
@csrf @method('PUT')
<div class="card">
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label small fw-semibold">Nombre *</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre',$departamento->nombre) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label small fw-semibold">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion',$departamento->descripcion) }}</textarea>
        </div>
        <div class="form-check mb-3">
            <input type="checkbox" name="activo" value="1" class="form-check-input" id="activo" {{ $departamento->activo?'checked':'' }}>
            <label class="form-check-label" for="activo">Activo</label>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Actualizar</button>
        <a href="{{ route('departamentos.index') }}" class="btn btn-outline-secondary ms-2">Cancelar</a>
    </div>
</div>
</form>
</div></div>
@endsection
