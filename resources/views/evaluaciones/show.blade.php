@extends('layouts.app')
@section('title', 'Evaluación')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('evaluaciones.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div class="flex-grow-1">
        <h5 class="fw-bold mb-0 text-white">Evaluación — {{ $evaluacion->empleado->nombre_completo }}</h5>
        <small class="text-muted">Período: {{ $evaluacion->periodo }} · Evaluador: {{ $evaluacion->evaluador->name }}</small>
    </div>
    <span class="badge bg-{{ $evaluacion->calificacion_badge }} fs-6">{{ ucfirst($evaluacion->calificacion) }}</span>
    <a href="{{ route('evaluaciones.edit', $evaluacion) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
</div>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header fw-semibold">Puntaje Total</div>
            <div class="card-body text-center py-4">
                <div style="font-size:4rem;font-weight:700;color:{{ $evaluacion->puntaje_total>=90?'#198754':($evaluacion->puntaje_total>=70?'#0d6efd':($evaluacion->puntaje_total>=50?'#ffc107':'#dc3545')) }}">
                    {{ $evaluacion->puntaje_total }}
                </div>
                <div class="text-muted">de 100 puntos</div>
                <span class="badge bg-{{ $evaluacion->calificacion_badge }} mt-2 fs-6">{{ ucfirst($evaluacion->calificacion) }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card mb-3">
            <div class="card-header fw-semibold">Desglose por Criterio</div>
            <div class="card-body">
                @foreach([['Productividad',$evaluacion->puntaje_productividad],['Responsabilidad',$evaluacion->puntaje_responsabilidad],['Trabajo en Equipo',$evaluacion->puntaje_trabajo_equipo],['Calidad',$evaluacion->puntaje_calidad],['Cumplimiento',$evaluacion->puntaje_cumplimiento]] as [$n,$p])
                <div class="mb-3">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted">{{ $n }}</span>
                        <span class="fw-bold text-white">{{ $p }}/20</span>
                    </div>
                    <div class="progress" style="height:10px">
                        <div class="progress-bar bg-{{ $p>=16?'success':($p>=12?'primary':($p>=8?'warning':'danger')) }}"
                             style="width:{{ ($p/20)*100 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @if($evaluacion->comentarios)
        <div class="card">
            <div class="card-header fw-semibold">Comentarios</div>
            <div class="card-body text-muted">{{ $evaluacion->comentarios }}</div>
        </div>
        @endif
    </div>
</div>
@endsection
