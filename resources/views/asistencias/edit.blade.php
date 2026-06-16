@extends('layouts.app')
@section('title', 'Editar Asistencia')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('asistencias.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0 text-white">Editar Asistencia — {{ $asistencia->empleado->nombre_completo }}</h5>
</div>
<div class="row"><div class="col-lg-6">
<form action="{{ route('asistencias.update', $asistencia) }}" method="POST">
@csrf @method('PUT')
<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label small fw-semibold">Tipo *</label>
                <select name="tipo" class="form-select" required>
                    @foreach(['normal','tardanza','ausencia','permiso','vacacion','feriado'] as $t)
                    <option value="{{ $t }}" {{ $asistencia->tipo==$t?'selected':'' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6"><label class="form-label small fw-semibold">Fecha</label><input type="date" class="form-control" value="{{ $asistencia->fecha->format('Y-m-d') }}" disabled></div>
            <div class="col-md-6"><label class="form-label small fw-semibold">Hora Entrada</label><input type="time" name="hora_entrada" class="form-control" value="{{ $asistencia->hora_entrada }}"></div>
            <div class="col-md-6"><label class="form-label small fw-semibold">Hora Salida</label><input type="time" name="hora_salida" class="form-control" value="{{ $asistencia->hora_salida }}"></div>
            <div class="col-12"><label class="form-label small fw-semibold">Observaciones</label><textarea name="observaciones" class="form-control" rows="2">{{ $asistencia->observaciones }}</textarea></div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Actualizar</button>
        <a href="{{ route('asistencias.index') }}" class="btn btn-outline-secondary ms-2">Cancelar</a>
    </div>
</div>
</form>
</div></div>
@endsection
