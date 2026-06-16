@extends('layouts.app')
@section('title', 'Nueva Evaluación')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('evaluaciones.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0 text-white">Evaluación de Desempeño</h5>
</div>
<div class="row"><div class="col-lg-8">
<form action="{{ route('evaluaciones.store') }}" method="POST">
@csrf
<div class="card mb-3">
    <div class="card-header fw-semibold"><i class="bi bi-clipboard-check me-2" style="color:#4f8ef7"></i>Datos de la Evaluación</div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label small fw-semibold">Empleado *</label>
                <select name="empleado_id" class="form-select" required>
                    <option value="">— Seleccionar —</option>
                    @foreach($empleados as $e)
                    <option value="{{ $e->id }}" {{ $empleadoId==$e->id?'selected':'' }}>{{ $e->nombre_completo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Período *</label>
                <input type="text" name="periodo" class="form-control" placeholder="Ej: Q1 2026 / Enero-Marzo 2026" required>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header fw-semibold"><i class="bi bi-bar-chart me-2" style="color:#4f8ef7"></i>Criterios de Evaluación (1-20 cada uno)</div>
    <div class="card-body">
        <div class="row g-3">
            @foreach([['puntaje_productividad','Productividad','Cantidad y calidad del trabajo realizado'],['puntaje_responsabilidad','Responsabilidad','Cumplimiento de compromisos y puntualidad'],['puntaje_trabajo_equipo','Trabajo en Equipo','Colaboración y relaciones interpersonales'],['puntaje_calidad','Calidad','Precisión y excelencia en el trabajo'],['puntaje_cumplimiento','Cumplimiento','Adherencia a normas y procedimientos']] as [$field,$label,$desc])
            <div class="col-md-6">
                <label class="form-label small fw-semibold">{{ $label }} *</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="range" name="{{ $field }}" class="form-range flex-grow-1 puntaje-range"
                           min="1" max="20" value="{{ old($field, 15) }}"
                           oninput="updateVal(this);calcTotal()">
                    <span class="fw-bold text-white" style="min-width:40px;text-align:right" id="val_{{ $field }}">{{ old($field, 15) }}/20</span>
                </div>
                <small class="text-muted">{{ $desc }}</small>
                <input type="hidden" name="{{ $field }}_hidden" id="h_{{ $field }}">
            </div>
            @endforeach
            <div class="col-md-12 mt-2">
                <div class="d-flex align-items-center justify-content-between p-3 rounded" style="background:#1e2230;border:1px solid #2a2d35">
                    <span class="fw-bold text-white">Puntaje Total</span>
                    <div>
                        <span id="totalPuntaje" class="fw-bold fs-3 text-success">75</span>
                        <span class="text-muted">/100</span>
                        <span id="calificacionLabel" class="badge bg-success ms-2">Bueno</span>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Comentarios</label>
                <textarea name="comentarios" class="form-control" rows="3"></textarea>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Guardar Evaluación</button>
        <a href="{{ route('evaluaciones.index') }}" class="btn btn-outline-secondary ms-2">Cancelar</a>
    </div>
</div>
</form>
</div></div>
@push('scripts')
<script>
const fields=['puntaje_productividad','puntaje_responsabilidad','puntaje_trabajo_equipo','puntaje_calidad','puntaje_cumplimiento'];
function updateVal(el){const n=el.name;document.getElementById('val_'+n).textContent=el.value+'/20';}
function calcTotal(){
    let t=0;
    document.querySelectorAll('.puntaje-range').forEach(r=>t+=parseInt(r.value));
    document.getElementById('totalPuntaje').textContent=t;
    const lbl=document.getElementById('calificacionLabel');
    if(t>=90){lbl.textContent='Excelente';lbl.className='badge bg-success ms-2';}
    else if(t>=70){lbl.textContent='Bueno';lbl.className='badge bg-primary ms-2';}
    else if(t>=50){lbl.textContent='Regular';lbl.className='badge bg-warning ms-2';}
    else{lbl.textContent='Deficiente';lbl.className='badge bg-danger ms-2';}
}
calcTotal();
</script>
@endpush
@endsection
