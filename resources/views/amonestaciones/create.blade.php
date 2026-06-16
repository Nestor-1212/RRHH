@extends('layouts.app')
@section('title', 'Nueva Amonestación')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('amonestaciones.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0 text-white">Registrar Amonestación</h5>
</div>
<div class="row"><div class="col-lg-8">
<form action="{{ route('amonestaciones.store') }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="card mb-3">
    <div class="card-header fw-semibold"><i class="bi bi-exclamation-triangle me-2" style="color:#ffc107"></i>Datos de la Amonestación</div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Empleado *</label>
                <select name="empleado_id" class="form-select" required>
                    <option value="">— Seleccionar —</option>
                    @foreach($empleados as $e)
                    <option value="{{ $e->id }}" {{ ($empleadoId==$e->id)?'selected':'' }}>{{ $e->nombre_completo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Fecha *</label>
                <input type="date" name="fecha" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Tipo *</label>
                <select name="tipo" class="form-select" id="tipoAmon" required onchange="toggleSuspension()">
                    <option value="llamado_atencion">Llamado de Atención</option>
                    <option value="verbal">Amonestación Verbal</option>
                    <option value="escrita">Amonestación Escrita</option>
                    <option value="suspension">Suspensión</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Motivo *</label>
                <input type="text" name="motivo" class="form-control" required placeholder="Ej: Incumplimiento de horario laboral">
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Descripción detallada</label>
                <textarea name="descripcion" class="form-control" rows="3"></textarea>
            </div>
            <div class="col-md-4" id="diasSuspension" style="display:none">
                <label class="form-label small fw-semibold">Días de Suspensión</label>
                <input type="number" name="dias_suspension" class="form-control" min="1" max="30" value="1">
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header fw-semibold"><i class="bi bi-paperclip me-2" style="color:#4f8ef7"></i>Archivos Adjuntos</div>
    <div class="card-body">
        <div id="archivosContainer">
            <div class="row g-2 mb-2 archivo-row">
                <div class="col-md-4">
                    <select name="tipo_archivo[]" class="form-select form-select-sm">
                        <option value="documento">Documento firmado</option>
                        <option value="evidencia">Evidencia</option>
                        <option value="fotografia">Fotografía</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>
                <div class="col-md-8"><input type="file" name="archivos[]" class="form-control form-control-sm"></div>
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary mt-1" onclick="addArchivo()">
            <i class="bi bi-plus-lg me-1"></i>Agregar otro archivo
        </button>
    </div>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Guardar</button>
    <a href="{{ route('amonestaciones.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
</form>
</div></div>
@push('scripts')
<script>
function toggleSuspension(){
    document.getElementById('diasSuspension').style.display=document.getElementById('tipoAmon').value==='suspension'?'block':'none';
}
function addArchivo(){
    const t=document.querySelector('.archivo-row').cloneNode(true);
    t.querySelector('input[type="file"]').value='';
    document.getElementById('archivosContainer').appendChild(t);
}
</script>
@endpush
@endsection
