@extends('layouts.app')
@section('title', 'Detalle Entrevista')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('entrevistas.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div class="flex-grow-1">
        <h5 class="fw-bold mb-0 text-white">Entrevista — {{ $entrevista->candidato->nombre_completo }}</h5>
        <small class="text-muted">{{ $entrevista->vacante->nombre_puesto }} · {{ $entrevista->tipo_label }} · {{ $entrevista->fecha_entrevista->format('d/m/Y H:i') }}</small>
    </div>
    <span class="badge bg-{{ $entrevista->resultado_badge }} fs-6">{{ ucfirst(str_replace('_',' ',$entrevista->resultado)) }}</span>
    <a href="{{ route('entrevistas.edit', $entrevista) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil me-1"></i>Editar</a>
</div>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="card mb-3">
            <div class="card-header fw-semibold">Datos</div>
            <div class="card-body">
                @foreach([['Candidato',$entrevista->candidato->nombre_completo],['Vacante',$entrevista->vacante->nombre_puesto],['Entrevistador',$entrevista->entrevistador->name],['Tipo',$entrevista->tipo_label],['Fecha',$entrevista->fecha_entrevista->format('d/m/Y H:i')]] as [$l,$v])
                <div class="d-flex justify-content-between py-1 border-bottom border-secondary border-opacity-25 small">
                    <span class="text-muted">{{ $l }}</span><span class="text-white">{{ $v }}</span>
                </div>
                @endforeach
            </div>
        </div>

        @if($entrevista->resultado === 'no_seleccionado')
        <div class="card border-danger">
            <div class="card-header fw-semibold text-danger"><i class="bi bi-x-circle me-2"></i>Motivo de Rechazo</div>
            <div class="card-body">
                <p class="mb-1 text-white">{{ str_replace('_',' ',ucfirst($entrevista->motivo_rechazo ?? '—')) }}</p>
                @if($entrevista->detalle_rechazo)<p class="text-muted small mb-0">{{ $entrevista->detalle_rechazo }}</p>@endif
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-7">
        <div class="card mb-3">
            <div class="card-header fw-semibold"><i class="bi bi-bar-chart me-2" style="color:#4f8ef7"></i>Evaluación</div>
            <div class="card-body">
                @php
                    $criterios = [
                        ['Experiencia', $entrevista->puntaje_experiencia],
                        ['Conocimiento', $entrevista->puntaje_conocimiento],
                        ['Comunicación', $entrevista->puntaje_comunicacion],
                        ['Actitud', $entrevista->puntaje_actitud],
                        ['Disponibilidad', $entrevista->puntaje_disponibilidad],
                    ];
                @endphp
                @foreach($criterios as [$nombre, $puntaje])
                <div class="mb-2">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted">{{ $nombre }}</span>
                        <span class="fw-semibold text-white">{{ $puntaje }}/10</span>
                    </div>
                    <div class="progress" style="height:8px">
                        <div class="progress-bar bg-{{ $puntaje>=8?'success':($puntaje>=5?'warning':'danger') }}"
                             style="width:{{ $puntaje*10 }}%"></div>
                    </div>
                </div>
                @endforeach
                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-secondary">
                    <span class="fw-bold text-white">Puntaje Total</span>
                    <span class="fw-bold fs-4 text-{{ $entrevista->puntaje_total>=40?'success':($entrevista->puntaje_total>=25?'warning':'danger') }}">
                        {{ $entrevista->puntaje_total }}<span class="text-muted fs-6 fw-normal">/50</span>
                    </span>
                </div>
            </div>
        </div>
        @if($entrevista->fortalezas || $entrevista->debilidades || $entrevista->comentarios)
        <div class="card">
            <div class="card-header fw-semibold">Observaciones</div>
            <div class="card-body">
                @if($entrevista->fortalezas)
                <div class="mb-3"><div class="text-success fw-semibold small mb-1"><i class="bi bi-plus-circle me-1"></i>Fortalezas</div><p class="text-muted small mb-0">{{ $entrevista->fortalezas }}</p></div>
                @endif
                @if($entrevista->debilidades)
                <div class="mb-3"><div class="text-warning fw-semibold small mb-1"><i class="bi bi-dash-circle me-1"></i>Debilidades</div><p class="text-muted small mb-0">{{ $entrevista->debilidades }}</p></div>
                @endif
                @if($entrevista->comentarios)
                <div><div class="text-info fw-semibold small mb-1"><i class="bi bi-chat me-1"></i>Comentarios</div><p class="text-muted small mb-0">{{ $entrevista->comentarios }}</p></div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
