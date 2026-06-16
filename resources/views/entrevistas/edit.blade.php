@extends('layouts.app')
@section('title', 'Editar Entrevista')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('entrevistas.show', $entrevista) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0 text-white">Editar Entrevista</h5>
</div>
<form action="{{ route('entrevistas.update', $entrevista) }}" method="POST">
@csrf @method('PUT')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header fw-semibold">Información General</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Candidato</label>
                        <input type="text" class="form-control" value="{{ $entrevista->candidato->nombre_completo }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Vacante</label>
                        <input type="text" class="form-control" value="{{ $entrevista->vacante->nombre_puesto }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Entrevistador *</label>
                        <select name="entrevistador_id" class="form-select" required>
                            @foreach($entrevistadores as $u)
                            <option value="{{ $u->id }}" {{ $entrevista->entrevistador_id==$u->id?'selected':'' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Fecha y Hora *</label>
                        <input type="datetime-local" name="fecha_entrevista" class="form-control"
                               value="{{ old('fecha_entrevista', $entrevista->fecha_entrevista->format('Y-m-d\TH:i')) }}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Tipo *</label>
                        <select name="tipo" class="form-select">
                            @foreach(['inicial','tecnica','final'] as $t)
                            <option value="{{ $t }}" {{ $entrevista->tipo==$t?'selected':'' }}>{{ ucfirst($t) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header fw-semibold">Evaluación (1-10)</div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach([['puntaje_experiencia','Experiencia'],['puntaje_conocimiento','Conocimiento'],['puntaje_comunicacion','Comunicación'],['puntaje_actitud','Actitud'],['puntaje_disponibilidad','Disponibilidad']] as [$field,$label])
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">{{ $label }} *</label>
                        <input type="number" name="{{ $field }}" class="form-control puntaje-input"
                               min="1" max="10" value="{{ old($field, $entrevista->$field) }}" required oninput="calcTotal()">
                    </div>
                    @endforeach
                    <div class="col-md-2 d-flex flex-column justify-content-end">
                        <label class="form-label small fw-semibold text-muted">Total</label>
                        <div class="form-control fw-bold text-center" id="totalDisplay" style="background:#0d1117;color:#4f8ef7">{{ $entrevista->puntaje_total }}/50</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header fw-semibold">Resultado</div>
            <div class="card-body">
                <div class="mb-3">
                    <select name="resultado" id="resultadoSelect" class="form-select" required onchange="toggleRechazo()">
                        @foreach(['pendiente','seleccionado','no_seleccionado'] as $r)
                        <option value="{{ $r }}" {{ $entrevista->resultado==$r?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$r)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="motivoRechazo" style="display:{{ $entrevista->resultado=='no_seleccionado'?'block':'none' }}">
                    <label class="form-label small fw-semibold">Motivo</label>
                    <select name="motivo_rechazo" class="form-select mb-2">
                        @foreach(['falta_experiencia'=>'Falta de experiencia','otro_candidato'=>'Otro candidato','no_cumple_requisitos'=>'No cumple requisitos','pretension_salarial'=>'Pretensión salarial','no_aprobado'=>'No aprobado','otro'=>'Otro'] as $k=>$v)
                        <option value="{{ $k }}" {{ $entrevista->motivo_rechazo==$k?'selected':'' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                    <textarea name="detalle_rechazo" class="form-control" rows="2">{{ old('detalle_rechazo',$entrevista->detalle_rechazo) }}</textarea>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header fw-semibold">Observaciones</div>
            <div class="card-body">
                <div class="mb-2"><label class="form-label small">Fortalezas</label><textarea name="fortalezas" class="form-control" rows="2">{{ old('fortalezas',$entrevista->fortalezas) }}</textarea></div>
                <div class="mb-2"><label class="form-label small">Debilidades</label><textarea name="debilidades" class="form-control" rows="2">{{ old('debilidades',$entrevista->debilidades) }}</textarea></div>
                <div><label class="form-label small">Comentarios</label><textarea name="comentarios" class="form-control" rows="2">{{ old('comentarios',$entrevista->comentarios) }}</textarea></div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Actualizar</button>
        <a href="{{ route('entrevistas.show', $entrevista) }}" class="btn btn-outline-secondary ms-2">Cancelar</a>
    </div>
</div>
</form>
@push('scripts')
<script>
function calcTotal(){let t=0;document.querySelectorAll('.puntaje-input').forEach(i=>t+=parseInt(i.value||0));document.getElementById('totalDisplay').textContent=t+'/50';}
function toggleRechazo(){document.getElementById('motivoRechazo').style.display=document.getElementById('resultadoSelect').value==='no_seleccionado'?'block':'none';}
</script>
@endpush
@endsection
