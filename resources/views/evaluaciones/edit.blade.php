@extends('layouts.app')
@section('title', 'Editar Evaluación')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('evaluaciones.show', $evaluacion) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0 text-white">Editar Evaluación — {{ $evaluacion->empleado->nombre_completo }}</h5>
</div>
<div class="row"><div class="col-lg-8">
<form action="{{ route('evaluaciones.update', $evaluacion) }}" method="POST">
@csrf @method('PUT')
<div class="card mb-3">
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label small fw-semibold">Período *</label>
            <input type="text" name="periodo" class="form-control" value="{{ old('periodo',$evaluacion->periodo) }}" required>
        </div>
        <div class="row g-3">
            @foreach([['puntaje_productividad','Productividad'],['puntaje_responsabilidad','Responsabilidad'],['puntaje_trabajo_equipo','Trabajo en Equipo'],['puntaje_calidad','Calidad'],['puntaje_cumplimiento','Cumplimiento']] as [$field,$label])
            <div class="col-md-6">
                <label class="form-label small fw-semibold">{{ $label }} *</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="range" name="{{ $field }}" class="form-range flex-grow-1 puntaje-range"
                           min="1" max="20" value="{{ old($field, $evaluacion->$field) }}"
                           oninput="updateVal(this);calcTotal()">
                    <span class="fw-bold text-white" style="min-width:40px;text-align:right" id="val_{{ $field }}">{{ old($field, $evaluacion->$field) }}/20</span>
                </div>
            </div>
            @endforeach
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between p-3 rounded" style="background:#1e2230;border:1px solid #2a2d35">
                    <span class="fw-bold text-white">Puntaje Total</span>
                    <div><span id="totalPuntaje" class="fw-bold fs-3 text-success">{{ $evaluacion->puntaje_total }}</span><span class="text-muted">/100</span><span id="calificacionLabel" class="badge bg-{{ $evaluacion->calificacion_badge }} ms-2">{{ ucfirst($evaluacion->calificacion) }}</span></div>
                </div>
            </div>
            <div class="col-12"><label class="form-label small fw-semibold">Comentarios</label><textarea name="comentarios" class="form-control" rows="3">{{ old('comentarios',$evaluacion->comentarios) }}</textarea></div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Actualizar</button>
        <a href="{{ route('evaluaciones.show', $evaluacion) }}" class="btn btn-outline-secondary ms-2">Cancelar</a>
    </div>
</div>
</form>
</div></div>
@push('scripts')
<script>
function updateVal(el){document.getElementById('val_'+el.name).textContent=el.value+'/20';}
function calcTotal(){let t=0;document.querySelectorAll('.puntaje-range').forEach(r=>t+=parseInt(r.value));document.getElementById('totalPuntaje').textContent=t;const lbl=document.getElementById('calificacionLabel');if(t>=90){lbl.textContent='Excelente';lbl.className='badge bg-success ms-2';}else if(t>=70){lbl.textContent='Bueno';lbl.className='badge bg-primary ms-2';}else if(t>=50){lbl.textContent='Regular';lbl.className='badge bg-warning ms-2';}else{lbl.textContent='Deficiente';lbl.className='badge bg-danger ms-2';}}
</script>
@endpush
@endsection
