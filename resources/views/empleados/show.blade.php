@extends('layouts.app')
@section('title', $empleado->nombre_completo)

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('empleados.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div class="flex-grow-1">
        <h5 class="fw-bold mb-0 text-white">{{ $empleado->nombre_completo }}</h5>
        <small class="text-muted">{{ $empleado->cargo }} · {{ $empleado->departamento->nombre }} · {{ $empleado->codigo_empleado }}</small>
    </div>
    <span class="badge bg-{{ $empleado->estado_badge }} fs-6">{{ ucfirst($empleado->estado) }}</span>
    <div class="btn-group">
        <a href="{{ route('empleados.edit', $empleado) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil me-1"></i>Editar</a>
        <a href="{{ route('empleados.bitacora', $empleado) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-clock-history me-1"></i>Bitácora</a>
        @if(!$empleado->salida)
        <a href="{{ route('salidas.create', ['empleado_id'=>$empleado->id]) }}" class="btn btn-sm btn-outline-danger"><i class="bi bi-door-open me-1"></i>Registrar Salida</a>
        @endif
    </div>
</div>

<div class="row g-3">
    <!-- Panel izquierdo -->
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-body text-center py-4">
                @if($empleado->foto)
                    <img src="{{ asset('storage/'.$empleado->foto) }}" class="rounded-circle mb-3" width="100" height="100" style="object-fit:cover">
                @else
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                         style="width:100px;height:100px;background:rgba(79,142,247,.2);color:#4f8ef7;font-size:2.2rem;font-weight:700">
                        {{ strtoupper(substr($empleado->nombre,0,1).substr($empleado->apellido,0,1)) }}
                    </div>
                @endif
                <h6 class="fw-bold text-white mb-0">{{ $empleado->nombre_completo }}</h6>
                <div class="text-muted small mt-1">{{ $empleado->cargo }}</div>
                <div class="text-muted small">{{ $empleado->departamento->nombre }}</div>
                <div class="mt-2 text-success fw-semibold">${{ number_format($empleado->salario,2) }}</div>
            </div>
            <div class="card-footer">
                <div class="row g-2 text-center">
                    <div class="col-4">
                        <div class="fw-bold text-white">{{ $empleado->amonestaciones->count() }}</div>
                        <div class="text-muted" style="font-size:.72rem">Amonest.</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold text-white">{{ $empleado->evaluaciones->count() }}</div>
                        <div class="text-muted" style="font-size:.72rem">Evaluac.</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold text-white">{{ $empleado->documentos->count() }}</div>
                        <div class="text-muted" style="font-size:.72rem">Docs.</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header fw-semibold small">Datos Personales</div>
            <div class="card-body p-0">
                @foreach([['Cédula',$empleado->cedula],['Nacimiento',$empleado->fecha_nacimiento?->format('d/m/Y')],['Teléfono',$empleado->telefono],['Correo',$empleado->email],['Dirección',$empleado->direccion],['Emergencia',$empleado->contacto_emergencia.' '.$empleado->telefono_emergencia]] as [$l,$v])
                @if($v && trim($v))
                <div class="d-flex justify-content-between px-3 py-1 border-bottom border-secondary border-opacity-25" style="font-size:.8rem">
                    <span class="text-muted">{{ $l }}</span>
                    <span class="text-white text-end" style="max-width:60%">{{ $v }}</span>
                </div>
                @endif
                @endforeach
            </div>
        </div>
        <div class="card">
            <div class="card-header fw-semibold small">Información Laboral</div>
            <div class="card-body p-0">
                @foreach([['Código',$empleado->codigo_empleado],['Ingreso',$empleado->fecha_ingreso->format('d/m/Y')],['Contrato',ucfirst($empleado->tipo_contrato)],['Jornada',ucfirst($empleado->jornada)],['Horario',$empleado->horario],['Jefe',$empleado->jefe?->nombre_completo]] as [$l,$v])
                @if($v)
                <div class="d-flex justify-content-between px-3 py-1 border-bottom border-secondary border-opacity-25" style="font-size:.8rem">
                    <span class="text-muted">{{ $l }}</span>
                    <span class="text-white">{{ $v }}</span>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- Panel derecho (tabs) -->
    <div class="col-lg-8">
        <ul class="nav nav-tabs mb-3" id="empTabs">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tabSalarios">Salarios</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tabDocs">Documentos</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tabAmon">Amonestaciones</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tabEval">Evaluaciones</a></li>
        </ul>
        <div class="tab-content">
            <!-- Salarios -->
            <div class="tab-pane fade show active" id="tabSalarios">
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-semibold text-white">Historial Salarial</span>
                    <a href="{{ route('salarios.create', $empleado) }}" class="btn btn-sm btn-outline-success"><i class="bi bi-plus-lg me-1"></i>Registrar Cambio</a>
                </div>
                <div class="timeline">
                    @forelse($empleado->salarios as $sal)
                    <div class="timeline-item">
                        <div class="tl-dot bg-{{ $sal->tipo_badge }}">
                            <i class="bi bi-{{ $sal->tipo=='descuento'?'dash':'plus' }}"></i>
                        </div>
                        <div class="tl-content">
                            <div class="d-flex justify-content-between">
                                <span class="badge bg-{{ $sal->tipo_badge }}">{{ $sal->tipo_label }}</span>
                                <span class="text-muted small">{{ $sal->fecha->format('d/m/Y') }}</span>
                            </div>
                            <div class="mt-1">
                                @if($sal->salario_anterior)<span class="text-muted">${{ number_format($sal->salario_anterior,2) }}</span> → @endif
                                <span class="fw-bold text-white">${{ number_format($sal->salario_nuevo,2) }}</span>
                            </div>
                            @if($sal->motivo)<div class="text-muted small mt-1">{{ $sal->motivo }}</div>@endif
                        </div>
                    </div>
                    @empty
                    <p class="text-muted small">Sin movimientos salariales.</p>
                    @endforelse
                </div>
            </div>

            <!-- Documentos -->
            <div class="tab-pane fade" id="tabDocs">
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-semibold text-white">Documentos Adjuntos</span>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#formDoc">
                        <i class="bi bi-plus-lg me-1"></i>Adjuntar
                    </button>
                </div>
                <div class="collapse mb-3" id="formDoc">
                    <div class="card"><div class="card-body">
                    <form action="{{ route('empleados.documentos.store', $empleado) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-4">
                            <select name="tipo" class="form-select form-select-sm" required>
                                @foreach(['contrato'=>'Contrato','cedula'=>'Cédula','seguro_social'=>'Seguro Social','certificado'=>'Certificado','titulo'=>'Título','otro'=>'Otro'] as $k=>$v)
                                <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4"><input type="text" name="nombre" class="form-control form-control-sm" placeholder="Nombre del documento *" required></div>
                        <div class="col-md-4"><input type="file" name="archivo" class="form-control form-control-sm" required></div>
                        <div class="col-12"><button class="btn btn-sm btn-primary"><i class="bi bi-upload me-1"></i>Subir</button></div>
                    </div>
                    </form>
                    </div></div>
                </div>
                @forelse($empleado->documentos as $doc)
                <div class="d-flex align-items-center gap-2 mb-2 p-2 rounded" style="background:#1e2230;border:1px solid #2a2d35">
                    <i class="bi bi-file-earmark text-info fs-5"></i>
                    <div class="flex-grow-1">
                        <div class="fw-semibold text-white" style="font-size:.85rem">{{ $doc->nombre }}</div>
                        <div class="text-muted small">{{ $doc->tipo_label }}</div>
                    </div>
                    <a href="{{ asset('storage/'.$doc->archivo) }}" target="_blank" class="btn btn-sm btn-outline-info"><i class="bi bi-download"></i></a>
                    <form action="{{ route('empleados.documentos.destroy', $doc) }}" method="POST" onsubmit="return confirm('¿Eliminar?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
                @empty
                <p class="text-muted small">Sin documentos adjuntos.</p>
                @endforelse
            </div>

            <!-- Amonestaciones -->
            <div class="tab-pane fade" id="tabAmon">
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-semibold text-white">Historial de Amonestaciones</span>
                    <a href="{{ route('amonestaciones.create', ['empleado_id'=>$empleado->id]) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-plus-lg me-1"></i>Nueva</a>
                </div>
                <div class="timeline">
                    @forelse($empleado->amonestaciones as $amon)
                    <div class="timeline-item">
                        <div class="tl-dot bg-{{ $amon->tipo_badge }}">
                            <i class="bi bi-exclamation"></i>
                        </div>
                        <div class="tl-content">
                            <div class="d-flex justify-content-between">
                                <span class="badge bg-{{ $amon->tipo_badge }}">{{ $amon->tipo_label }}</span>
                                <span class="text-muted small">{{ $amon->fecha->format('d/m/Y') }}</span>
                            </div>
                            <div class="text-white mt-1 small">{{ $amon->motivo }}</div>
                            @if($amon->dias_suspension > 0)<div class="text-warning small mt-1">Suspensión: {{ $amon->dias_suspension }} días</div>@endif
                            <a href="{{ route('amonestaciones.show', $amon) }}" class="btn btn-xs btn-outline-secondary mt-1" style="font-size:.75rem;padding:.15rem .5rem">Ver detalle</a>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted small">Sin amonestaciones.</p>
                    @endforelse
                </div>
            </div>

            <!-- Evaluaciones -->
            <div class="tab-pane fade" id="tabEval">
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-semibold text-white">Evaluaciones de Desempeño</span>
                    <a href="{{ route('evaluaciones.create', ['empleado_id'=>$empleado->id]) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-lg me-1"></i>Nueva Evaluación</a>
                </div>
                @forelse($empleado->evaluaciones as $eval)
                <div class="card mb-2">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fw-semibold text-white">{{ $eval->periodo }}</span>
                                <span class="badge bg-{{ $eval->calificacion_badge }} ms-2">{{ ucfirst($eval->calificacion) }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold text-white">{{ $eval->puntaje_total }}/100</span>
                                <a href="{{ route('evaluaciones.show', $eval) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted small">Sin evaluaciones registradas.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
