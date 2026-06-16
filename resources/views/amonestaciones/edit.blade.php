@extends('layouts.app')
@section('title', 'Editar Amonestación')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('amonestaciones.show', $amonestacion) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0 text-white">Editar Amonestación</h5>
</div>
<div class="row"><div class="col-lg-8">
<form action="{{ route('amonestaciones.update', $amonestacion) }}" method="POST" enctype="multipart/form-data">
@csrf @method('PUT')
<div class="card mb-3">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label small fw-semibold">Empleado</label><input type="text" class="form-control" value="{{ $amonestacion->empleado->nombre_completo }}" disabled></div>
            <div class="col-md-3"><label class="form-label small fw-semibold">Fecha *</label><input type="date" name="fecha" class="form-control" value="{{ $amonestacion->fecha->format('Y-m-d') }}" required></div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Tipo *</label>
                <select name="tipo" class="form-select" id="tipoAmon" onchange="toggleSuspension()" required>
                    @foreach(['llamado_atencion'=>'Llamado de Atención','verbal'=>'Verbal','escrita'=>'Escrita','suspension'=>'Suspensión'] as $k=>$v)
                    <option value="{{ $k }}" {{ $amonestacion->tipo==$k?'selected':'' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12"><label class="form-label small fw-semibold">Motivo *</label><input type="text" name="motivo" class="form-control" value="{{ $amonestacion->motivo }}" required></div>
            <div class="col-12"><label class="form-label small fw-semibold">Descripción</label><textarea name="descripcion" class="form-control" rows="3">{{ $amonestacion->descripcion }}</textarea></div>
            <div class="col-md-4" id="diasSuspension" style="display:{{ $amonestacion->tipo=='suspension'?'block':'none' }}">
                <label class="form-label small fw-semibold">Días de Suspensión</label>
                <input type="number" name="dias_suspension" class="form-control" min="1" value="{{ $amonestacion->dias_suspension }}">
            </div>
        </div>
    </div>
</div>
<div class="card mb-3">
    <div class="card-header fw-semibold">Agregar más archivos</div>
    <div class="card-body">
        <div class="row g-2">
            <div class="col-md-4"><select name="tipo_archivo[]" class="form-select form-select-sm"><option value="documento">Documento</option><option value="evidencia">Evidencia</option><option value="fotografia">Fotografía</option><option value="otro">Otro</option></select></div>
            <div class="col-md-8"><input type="file" name="archivos[]" class="form-control form-control-sm"></div>
        </div>
    </div>
</div>
<button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Actualizar</button>
<a href="{{ route('amonestaciones.show', $amonestacion) }}" class="btn btn-outline-secondary ms-2">Cancelar</a>
</form>
</div></div>
@push('scripts')
<script>
function toggleSuspension(){document.getElementById('diasSuspension').style.display=document.getElementById('tipoAmon').value==='suspension'?'block':'none';}
</script>
@endpush
@endsection
