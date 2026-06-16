@extends('layouts.app')
@section('title', 'Registrar Asistencia')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('asistencias.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0 text-white">Registrar Asistencia</h5>
</div>
<div class="row"><div class="col-lg-6">
<form action="{{ route('asistencias.store') }}" method="POST">
@csrf
<div class="card">
    <div class="card-header fw-semibold"><i class="bi bi-calendar-check me-2" style="color:#4f8ef7"></i>Datos</div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label small fw-semibold">Empleado *</label>
                <select name="empleado_id" class="form-select" required>
                    <option value="">— Seleccionar —</option>
                    @foreach($empleados as $e)
                    <option value="{{ $e->id }}">{{ $e->nombre_completo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Fecha *</label>
                <input type="date" name="fecha" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Tipo *</label>
                <select name="tipo" class="form-select" required>
                    <option value="normal">Normal</option>
                    <option value="tardanza">Tardanza</option>
                    <option value="ausencia">Ausencia</option>
                    <option value="permiso">Permiso</option>
                    <option value="vacacion">Vacación</option>
                    <option value="feriado">Feriado</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Hora de Entrada</label>
                <input type="time" name="hora_entrada" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Hora de Salida</label>
                <input type="time" name="hora_salida" class="form-control">
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Observaciones</label>
                <textarea name="observaciones" class="form-control" rows="2"></textarea>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Guardar</button>
        <a href="{{ route('asistencias.index') }}" class="btn btn-outline-secondary ms-2">Cancelar</a>
    </div>
</div>
</form>
</div></div>
@endsection
