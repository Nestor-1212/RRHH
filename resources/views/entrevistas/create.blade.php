@extends('layouts.app')
@section('title', 'Nueva Entrevista')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('entrevistas.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0 text-white">Registrar Entrevista</h5>
</div>
<form action="{{ route('entrevistas.store') }}" method="POST">
@csrf
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header fw-semibold"><i class="bi bi-info-circle me-2" style="color:#4f8ef7"></i>Información General</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Candidato *</label>
                        <select name="candidato_id" class="form-select" required>
                            <option value="">— Seleccionar —</option>
                            @foreach($candidatos as $c)
                            <option value="{{ $c->id }}" {{ (old('candidato_id',$candidatoId)==$c->id)?'selected':'' }}>{{ $c->nombre_completo }} · {{ $c->cedula }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Vacante *</label>
                        <select name="vacante_id" class="form-select" required>
                            <option value="">— Seleccionar —</option>
                            @foreach($vacantes as $v)
                            <option value="{{ $v->id }}" {{ old('vacante_id')==$v->id?'selected':'' }}>{{ $v->nombre_puesto }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Entrevistador *</label>
                        <select name="entrevistador_id" class="form-select" required>
                            <option value="">— Seleccionar —</option>
                            @foreach($entrevistadores as $u)
                            <option value="{{ $u->id }}" {{ old('entrevistador_id')==$u->id?'selected':'' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Fecha y Hora *</label>
                        <input type="datetime-local" name="fecha_entrevista" class="form-control" value="{{ old('fecha_entrevista') }}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Tipo *</label>
                        <select name="tipo" class="form-select" required>
                            <option value="inicial">Inicial</option>
                            <option value="tecnica">Técnica</option>
                            <option value="final">Final</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header fw-semibold"><i class="bi bi-clipboard me-2" style="color:#4f8ef7"></i>Evaluación (1-10 por criterio)</div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach([['puntaje_experiencia','Experiencia'],['puntaje_conocimiento','Conocimiento'],['puntaje_comunicacion','Comunicación'],['puntaje_actitud','Actitud'],['puntaje_disponibilidad','Disponibilidad']] as [$field,$label])
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">{{ $label }} *</label>
                        <input type="number" name="{{ $field }}" class="form-control puntaje-input"
                               min="1" max="10" value="{{ old($field, 5) }}" required oninput="calcTotal()">
                    </div>
                    @endforeach
                    <div class="col-md-2 d-flex flex-column justify-content-end">
                        <label class="form-label small fw-semibold text-muted">Total</label>
                        <div class="form-control fw-bold text-center" id="totalDisplay" style="background:#0d1117;color:#4f8ef7">25/50</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header fw-semibold"><i class="bi bi-check-square me-2" style="color:#4f8ef7"></i>Resultado</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Resultado *</label>
                    <select name="resultado" id="resultadoSelect" class="form-select" required onchange="toggleRechazo()">
                        <option value="pendiente">Pendiente</option>
                        <option value="seleccionado">✅ Seleccionado</option>
                        <option value="no_seleccionado">❌ No Seleccionado</option>
                    </select>
                </div>
                <div id="motivoRechazo" style="display:none">
                    <label class="form-label small fw-semibold">Motivo de Rechazo</label>
                    <select name="motivo_rechazo" class="form-select mb-2">
                        <option value="falta_experiencia">Falta de experiencia</option>
                        <option value="otro_candidato">Otro candidato seleccionado</option>
                        <option value="no_cumple_requisitos">No cumple requisitos</option>
                        <option value="pretension_salarial">Pretensión salarial</option>
                        <option value="no_aprobado">No aprobado entrevista</option>
                        <option value="otro">Otro</option>
                    </select>
                    <textarea name="detalle_rechazo" class="form-control" rows="2" placeholder="Detalles..."></textarea>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header fw-semibold">Observaciones</div>
            <div class="card-body">
                <div class="mb-2"><label class="form-label small fw-semibold">Fortalezas</label><textarea name="fortalezas" class="form-control" rows="2">{{ old('fortalezas') }}</textarea></div>
                <div class="mb-2"><label class="form-label small fw-semibold">Debilidades</label><textarea name="debilidades" class="form-control" rows="2">{{ old('debilidades') }}</textarea></div>
                <div><label class="form-label small fw-semibold">Comentarios</label><textarea name="comentarios" class="form-control" rows="2">{{ old('comentarios') }}</textarea></div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Registrar Entrevista</button>
        <a href="{{ route('entrevistas.index') }}" class="btn btn-outline-secondary ms-2">Cancelar</a>
    </div>
</div>
</form>
@push('scripts')
<script>
function calcTotal(){
    let total=0;
    document.querySelectorAll('.puntaje-input').forEach(i=>total+=parseInt(i.value||0));
    document.getElementById('totalDisplay').textContent=total+'/50';
}
function toggleRechazo(){
    const v=document.getElementById('resultadoSelect').value;
    document.getElementById('motivoRechazo').style.display=v==='no_seleccionado'?'block':'none';
}
</script>
@endpush
@endsection
