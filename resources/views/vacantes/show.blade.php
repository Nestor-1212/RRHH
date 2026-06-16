@extends('layouts.app')
@section('title', $vacante->nombre_puesto)

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('vacantes.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div class="flex-grow-1">
        <h5 class="fw-bold mb-0 text-white">{{ $vacante->nombre_puesto }}</h5>
        <small class="text-muted">{{ $vacante->departamento->nombre }} · {{ ucfirst(str_replace('_',' ',$vacante->tipo_contrato)) }}</small>
    </div>
    <span class="badge bg-{{ $vacante->estado_badge }} fs-6">{{ ucfirst(str_replace('_',' ',$vacante->estado)) }}</span>
    <a href="{{ route('vacantes.edit', $vacante) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil me-1"></i>Editar</a>
    <a href="{{ route('entrevistas.create', ['vacante_id' => $vacante->id]) }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Agregar Entrevista</a>
</div>
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header fw-semibold">Información</div>
            <div class="card-body">
                @foreach([['Salario','$'.number_format($vacante->salario_ofrecido??0,2)],['Apertura',$vacante->fecha_apertura->format('d/m/Y')],['Cierre',$vacante->fecha_cierre?->format('d/m/Y')??'Sin fecha'],['Entrevistas',$vacante->entrevistas->count().' realizadas']] as [$l,$v2])
                <div class="d-flex justify-content-between py-1 border-bottom border-secondary border-opacity-25 small">
                    <span class="text-muted">{{ $l }}</span><span class="text-white">{{ $v2 }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        @if($vacante->descripcion)<div class="card mb-3"><div class="card-header fw-semibold">Descripción</div><div class="card-body text-muted" style="font-size:.88rem">{!! nl2br(e($vacante->descripcion)) !!}</div></div>@endif
        @if($vacante->requisitos)<div class="card mb-3"><div class="card-header fw-semibold">Requisitos</div><div class="card-body text-muted" style="font-size:.88rem">{!! nl2br(e($vacante->requisitos)) !!}</div></div>@endif
        @if($vacante->entrevistas->count())
        <div class="card">
            <div class="card-header fw-semibold">Entrevistas para esta Vacante</div>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead><tr><th>Candidato</th><th>Fecha</th><th>Tipo</th><th>Puntaje</th><th>Resultado</th></tr></thead>
                    <tbody>
                        @foreach($vacante->entrevistas as $ent)
                        <tr>
                            <td><a href="{{ route('candidatos.show', $ent->candidato) }}" class="text-info">{{ $ent->candidato->nombre_completo }}</a></td>
                            <td class="text-muted small">{{ $ent->fecha_entrevista->format('d/m/Y') }}</td>
                            <td><span class="badge bg-secondary">{{ $ent->tipo_label }}</span></td>
                            <td><strong>{{ $ent->puntaje_total }}</strong>/50</td>
                            <td><span class="badge bg-{{ $ent->resultado_badge }}">{{ ucfirst(str_replace('_',' ',$ent->resultado)) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
