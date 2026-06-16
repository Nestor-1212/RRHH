@extends('layouts.app')
@section('title', 'Nuevo Candidato')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('candidatos.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h5 class="fw-bold mb-0 text-white">Nuevo Candidato</h5>
        <small class="text-muted">Registrar aspirante al proceso de selección</small>
    </div>
</div>

<form action="{{ route('candidatos.store') }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header fw-semibold"><i class="bi bi-person me-2" style="color:#4f8ef7"></i>Datos Personales</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Apellido <span class="text-danger">*</span></label>
                        <input type="text" name="apellido" class="form-control" value="{{ old('apellido') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Cédula / ID <span class="text-danger">*</span></label>
                        <input type="text" name="cedula" class="form-control" value="{{ old('cedula') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Aspiración Salarial</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary">$</span>
                            <input type="number" name="aspiracion_salarial" class="form-control" step="0.01" min="0" value="{{ old('aspiracion_salarial') }}">
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Dirección</label>
                        <textarea name="direccion" class="form-control" rows="2">{{ old('direccion') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Notas adicionales</label>
                        <textarea name="notas" class="form-control" rows="2">{{ old('notas') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header fw-semibold"><i class="bi bi-image me-2" style="color:#4f8ef7"></i>Foto y CV</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Foto (JPG/PNG, máx. 2MB)</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                </div>
                <div>
                    <label class="form-label small fw-semibold">Hoja de Vida (PDF, máx. 5MB)</label>
                    <input type="file" name="hoja_vida" class="form-control" accept=".pdf">
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header fw-semibold"><i class="bi bi-gear me-2" style="color:#4f8ef7"></i>Estado</div>
            <div class="card-body">
                <select name="estado" class="form-select">
                    <option value="activo">Activo</option>
                    <option value="en_proceso">En Proceso</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Guardar Candidato</button>
            <a href="{{ route('candidatos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </div>
</div>
</form>
@endsection
