@extends('layouts.app')
@section('title', 'Nuevo Empleado')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('empleados.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h5 class="fw-bold mb-0 text-white">Nuevo Empleado</h5>
        <small class="text-muted">Crear expediente del colaborador</small>
    </div>
</div>

<form action="{{ route('empleados.store') }}" method="POST" enctype="multipart/form-data">
@csrf
@if($candidato)
<input type="hidden" name="candidato_id" value="{{ $candidato->id }}">
<div class="alert alert-info d-flex align-items-center gap-2 mb-3">
    <i class="bi bi-person-check"></i>
    <span>Contratando candidato: <strong>{{ $candidato->nombre_completo }}</strong> · {{ $candidato->cedula }}</span>
</div>
@endif
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header fw-semibold"><i class="bi bi-person me-2" style="color:#4f8ef7"></i>Datos Personales</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nombre *</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $candidato?->nombre) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Apellido *</label>
                        <input type="text" name="apellido" class="form-control" value="{{ old('apellido', $candidato?->apellido) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Cédula *</label>
                        <input type="text" name="cedula" class="form-control" value="{{ old('cedula', $candidato?->cedula) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Fecha Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento', $candidato?->fecha_nacimiento?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $candidato?->telefono) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Correo</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $candidato?->email) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Dirección</label>
                        <textarea name="direccion" class="form-control" rows="2">{{ old('direccion', $candidato?->direccion) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Contacto de Emergencia</label>
                        <input type="text" name="contacto_emergencia" class="form-control" value="{{ old('contacto_emergencia') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Teléfono Emergencia</label>
                        <input type="text" name="telefono_emergencia" class="form-control" value="{{ old('telefono_emergencia') }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header fw-semibold"><i class="bi bi-building me-2" style="color:#4f8ef7"></i>Información Laboral</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Fecha Ingreso *</label>
                        <input type="date" name="fecha_ingreso" class="form-control" value="{{ old('fecha_ingreso', now()->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label small fw-semibold">Cargo *</label>
                        <input type="text" name="cargo" class="form-control" value="{{ old('cargo') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Departamento *</label>
                        <select name="departamento_id" class="form-select" required>
                            <option value="">— Seleccionar —</option>
                            @foreach($departamentos as $d)
                            <option value="{{ $d->id }}" {{ old('departamento_id')==$d->id?'selected':'' }}>{{ $d->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Jefe Inmediato</label>
                        <select name="jefe_id" class="form-select">
                            <option value="">— Ninguno —</option>
                            @foreach($jefes as $j)
                            <option value="{{ $j->id }}" {{ old('jefe_id')==$j->id?'selected':'' }}>{{ $j->nombre_completo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Tipo de Contrato *</label>
                        <select name="tipo_contrato" class="form-select" required>
                            @foreach(['indefinido','definido','servicios','pasantia'] as $t)
                            <option value="{{ $t }}">{{ ucfirst($t) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Salario *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary">$</span>
                            <input type="number" name="salario" class="form-control" step="0.01" min="0" value="{{ old('salario') }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Jornada *</label>
                        <select name="jornada" class="form-select" required>
                            <option value="completa">Completa</option>
                            <option value="parcial">Parcial</option>
                            <option value="mixta">Mixta</option>
                            <option value="nocturna">Nocturna</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Horario</label>
                        <input type="text" name="horario" class="form-control" placeholder="Ej: Lun-Vie 8am-5pm" value="{{ old('horario') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header fw-semibold"><i class="bi bi-image me-2" style="color:#4f8ef7"></i>Foto</div>
            <div class="card-body">
                <input type="file" name="foto" class="form-control" accept="image/*">
                <small class="text-muted">JPG/PNG, máx. 2MB</small>
            </div>
        </div>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Registrar Empleado</button>
        <a href="{{ route('empleados.index') }}" class="btn btn-outline-secondary ms-2">Cancelar</a>
    </div>
</div>
</form>
@endsection
