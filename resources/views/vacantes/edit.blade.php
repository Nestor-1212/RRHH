@extends('layouts.app')
@section('title', 'Editar Vacante')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('vacantes.show', $vacante) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0 text-white">Editar Vacante — {{ $vacante->nombre_puesto }}</h5>
</div>
<div class="row"><div class="col-lg-8">
<form action="{{ route('vacantes.update', $vacante) }}" method="POST">
@csrf @method('PUT')
<div class="card">
    <div class="card-header fw-semibold"><i class="bi bi-briefcase me-2" style="color:#4f8ef7"></i>Datos de la Vacante</div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label small fw-semibold">Nombre del Puesto *</label>
                <input type="text" name="nombre_puesto" class="form-control" value="{{ old('nombre_puesto',$vacante->nombre_puesto) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Departamento *</label>
                <select name="departamento_id" class="form-select" required>
                    @foreach($departamentos as $d)
                    <option value="{{ $d->id }}" {{ $vacante->departamento_id==$d->id?'selected':'' }}>{{ $d->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Tipo de Contrato *</label>
                <select name="tipo_contrato" class="form-select" required>
                    @foreach(['indefinido','definido','servicios','pasantia'] as $t)
                    <option value="{{ $t }}" {{ $vacante->tipo_contrato==$t?'selected':'' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Salario Ofrecido</label>
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary">$</span>
                    <input type="number" name="salario_ofrecido" class="form-control" step="0.01" value="{{ old('salario_ofrecido',$vacante->salario_ofrecido) }}">
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Estado</label>
                <select name="estado" class="form-select">
                    @foreach(['disponible','en_proceso','cerrada'] as $e)
                    <option value="{{ $e }}" {{ $vacante->estado==$e?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$e)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Fecha Apertura *</label>
                <input type="date" name="fecha_apertura" class="form-control" value="{{ old('fecha_apertura',$vacante->fecha_apertura->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Fecha Cierre</label>
                <input type="date" name="fecha_cierre" class="form-control" value="{{ old('fecha_cierre',$vacante->fecha_cierre?->format('Y-m-d')) }}">
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion',$vacante->descripcion) }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Requisitos</label>
                <textarea name="requisitos" class="form-control" rows="3">{{ old('requisitos',$vacante->requisitos) }}</textarea>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Actualizar</button>
        <a href="{{ route('vacantes.show', $vacante) }}" class="btn btn-outline-secondary ms-2">Cancelar</a>
    </div>
</div>
</form>
</div></div>
@endsection
