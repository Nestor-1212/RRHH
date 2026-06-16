@extends('layouts.app')
@section('title', 'Nueva Vacante')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('vacantes.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0 text-white">Nueva Vacante</h5>
</div>
<div class="row"><div class="col-lg-8">
<form action="{{ route('vacantes.store') }}" method="POST">
@csrf
<div class="card">
    <div class="card-header fw-semibold"><i class="bi bi-briefcase me-2" style="color:#4f8ef7"></i>Datos de la Vacante</div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label small fw-semibold">Nombre del Puesto *</label>
                <input type="text" name="nombre_puesto" class="form-control" value="{{ old('nombre_puesto') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Departamento *</label>
                <select name="departamento_id" class="form-select" required>
                    <option value="">— Seleccionar —</option>
                    @foreach($departamentos as $d)
                    <option value="{{ $d->id }}" {{ old('departamento_id')==$d->id?'selected':'' }}>{{ $d->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Tipo de Contrato *</label>
                <select name="tipo_contrato" class="form-select" required>
                    <option value="indefinido">Indefinido</option>
                    <option value="definido">Definido</option>
                    <option value="servicios">Servicios</option>
                    <option value="pasantia">Pasantía</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Salario Ofrecido</label>
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary">$</span>
                    <input type="number" name="salario_ofrecido" class="form-control" step="0.01" value="{{ old('salario_ofrecido') }}">
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Estado</label>
                <select name="estado" class="form-select">
                    <option value="disponible">Disponible</option>
                    <option value="en_proceso">En Proceso</option>
                    <option value="cerrada">Cerrada</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Fecha Apertura *</label>
                <input type="date" name="fecha_apertura" class="form-control" value="{{ old('fecha_apertura', now()->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Fecha Cierre</label>
                <input type="date" name="fecha_cierre" class="form-control" value="{{ old('fecha_cierre') }}">
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Descripción del Cargo</label>
                <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion') }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Requisitos</label>
                <textarea name="requisitos" class="form-control" rows="3">{{ old('requisitos') }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Notas</label>
                <textarea name="notas" class="form-control" rows="2">{{ old('notas') }}</textarea>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Guardar</button>
        <a href="{{ route('vacantes.index') }}" class="btn btn-outline-secondary ms-2">Cancelar</a>
    </div>
</div>
</form>
</div></div>
@endsection
