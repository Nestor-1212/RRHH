@extends('layouts.app')
@section('title', 'Nuevo Departamento')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('departamentos.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0 text-white">Nuevo Departamento</h5>
</div>
<div class="row"><div class="col-md-5">
<form action="{{ route('departamentos.store') }}" method="POST">
@csrf
<div class="card">
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label small fw-semibold">Nombre *</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label small fw-semibold">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion') }}</textarea>
        </div>
        <div class="form-check mb-3">
            <input type="checkbox" name="activo" value="1" class="form-check-input" id="activo" checked>
            <label class="form-check-label" for="activo">Activo</label>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Guardar</button>
        <a href="{{ route('departamentos.index') }}" class="btn btn-outline-secondary ms-2">Cancelar</a>
    </div>
</div>
</form>
</div></div>
@endsection
