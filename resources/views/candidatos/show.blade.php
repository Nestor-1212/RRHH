@extends('layouts.app')
@section('title', $candidato->nombre_completo)

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('candidatos.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div class="flex-grow-1">
        <h5 class="fw-bold mb-0 text-white">{{ $candidato->nombre_completo }}</h5>
        <small class="text-muted">Perfil del candidato · {{ $candidato->cedula }}</small>
    </div>
    <a href="{{ route('candidatos.edit', $candidato) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil me-1"></i> Editar</a>
    <a href="{{ route('entrevistas.create', ['candidato_id' => $candidato->id]) }}" class="btn btn-sm btn-primary"><i class="bi bi-chat-dots me-1"></i> Nueva Entrevista</a>
    <a href="{{ route('empleados.create', ['candidato_id' => $candidato->id]) }}" class="btn btn-sm btn-success"><i class="bi bi-person-check me-1"></i> Contratar</a>
</div>

<div class="row g-3">
    <!-- Info principal -->
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-body text-center py-4">
                @if($candidato->foto)
                    <img src="{{ asset('storage/'.$candidato->foto) }}" class="rounded-circle mb-3" width="90" height="90" style="object-fit:cover">
                @else
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-secondary mx-auto mb-3" style="width:90px;height:90px;font-size:2rem">
                        {{ strtoupper(substr($candidato->nombre,0,1).substr($candidato->apellido,0,1)) }}
                    </div>
                @endif
                <h6 class="fw-bold text-white mb-1">{{ $candidato->nombre_completo }}</h6>
                <span class="badge bg-{{ ['activo'=>'success','en_proceso'=>'warning','contratado'=>'primary','descartado'=>'danger'][$candidato->estado] ?? 'secondary' }}">
                    {{ ucfirst(str_replace('_',' ',$candidato->estado)) }}
                </span>
                @if($candidato->hoja_vida)
                    <div class="mt-3">
                        <a href="{{ asset('storage/'.$candidato->hoja_vida) }}" target="_blank" class="btn btn-sm btn-outline-info w-100">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Ver Hoja de Vida
                        </a>
                    </div>
                @endif
            </div>
            <div class="card-footer">
                <div class="row g-2 text-center">
                    <div class="col-4">
                        <div class="fw-bold text-white">{{ $candidato->entrevistas->count() }}</div>
                        <div class="text-muted" style="font-size:.72rem">Entrevistas</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold text-white">{{ $candidato->estudios->count() }}</div>
                        <div class="text-muted" style="font-size:.72rem">Estudios</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold text-white">{{ $candidato->experiencias->count() }}</div>
                        <div class="text-muted" style="font-size:.72rem">Exp. Lab.</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Datos personales -->
        <div class="card">
            <div class="card-header fw-semibold small">Datos Personales</div>
            <div class="card-body">
                @foreach([['Cédula',$candidato->cedula],['Nacimiento',$candidato->fecha_nacimiento?->format('d/m/Y')],['Teléfono',$candidato->telefono],['Correo',$candidato->email],['Dirección',$candidato->direccion],['Aspiración',$candidato->aspiracion_salarial?'$'.number_format($candidato->aspiracion_salarial,2):null]] as [$l,$v])
                @if($v)
                <div class="d-flex justify-content-between py-1 border-bottom border-secondary border-opacity-25" style="font-size:.83rem">
                    <span class="text-muted">{{ $l }}</span>
                    <span class="text-white text-end" style="max-width:60%">{{ $v }}</span>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Estudios -->
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-mortarboard me-2" style="color:#4f8ef7"></i>Estudios</span>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#formEstudio">
                    <i class="bi bi-plus"></i>
                </button>
            </div>
            <div class="collapse" id="formEstudio">
                <form action="{{ route('candidatos.estudios.store', $candidato) }}" method="POST">
                @csrf
                <div class="card-body border-bottom border-secondary border-opacity-25">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <select name="nivel" class="form-select form-select-sm" required>
                                <option value="">Nivel...</option>
                                @foreach(['primaria','secundaria','bachillerato','tecnico','universitario','postgrado','maestria','doctorado'] as $n)
                                <option value="{{ $n }}">{{ ucfirst($n) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4"><input type="text" name="institucion" class="form-control form-control-sm" placeholder="Institución *" required></div>
                        <div class="col-md-4"><input type="text" name="carrera" class="form-control form-control-sm" placeholder="Carrera"></div>
                        <div class="col-md-3"><input type="number" name="anio_inicio" class="form-control form-control-sm" placeholder="Año inicio" min="1950" max="2030"></div>
                        <div class="col-md-3"><input type="number" name="anio_fin" class="form-control form-control-sm" placeholder="Año fin" min="1950" max="2030"></div>
                        <div class="col-md-3 d-flex align-items-center">
                            <div class="form-check"><input type="checkbox" name="graduado" value="1" class="form-check-input" id="grad"><label class="form-check-label small" for="grad">Graduado</label></div>
                        </div>
                        <div class="col-md-3 text-end"><button class="btn btn-sm btn-primary w-100">Agregar</button></div>
                    </div>
                </div>
                </form>
            </div>
            <div class="card-body p-0">
                @forelse($candidato->estudios as $est)
                <div class="d-flex align-items-center gap-3 px-3 py-2 border-bottom border-secondary border-opacity-25">
                    <i class="bi bi-book text-info"></i>
                    <div class="flex-grow-1">
                        <div class="fw-semibold text-white" style="font-size:.85rem">{{ $est->nivel_label }} — {{ $est->carrera ?? 'Sin carrera' }}</div>
                        <div class="text-muted small">{{ $est->institucion }} @if($est->anio_inicio) · {{ $est->anio_inicio }}–{{ $est->anio_fin ?? 'Presente' }} @endif</div>
                    </div>
                    @if($est->graduado)<span class="badge bg-success me-2">Graduado</span>@endif
                    <form action="{{ route('candidatos.estudios.destroy', $est) }}" method="POST" onsubmit="return confirm('¿Eliminar?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
                @empty
                <div class="text-center py-3 text-muted small">Sin estudios registrados</div>
                @endforelse
            </div>
        </div>

        <!-- Experiencia -->
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-building me-2" style="color:#4f8ef7"></i>Experiencia Laboral</span>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#formExp"><i class="bi bi-plus"></i></button>
            </div>
            <div class="collapse" id="formExp">
                <form action="{{ route('candidatos.experiencias.store', $candidato) }}" method="POST">
                @csrf
                <div class="card-body border-bottom border-secondary border-opacity-25">
                    <div class="row g-2">
                        <div class="col-md-6"><input type="text" name="empresa" class="form-control form-control-sm" placeholder="Empresa *" required></div>
                        <div class="col-md-6"><input type="text" name="cargo" class="form-control form-control-sm" placeholder="Cargo *" required></div>
                        <div class="col-md-3"><input type="date" name="fecha_inicio" class="form-control form-control-sm" required></div>
                        <div class="col-md-3"><input type="date" name="fecha_fin" class="form-control form-control-sm"></div>
                        <div class="col-md-3 d-flex align-items-center">
                            <div class="form-check"><input type="checkbox" name="actualmente" value="1" class="form-check-input" id="act"><label class="form-check-label small" for="act">Actualmente</label></div>
                        </div>
                        <div class="col-md-3 text-end"><button class="btn btn-sm btn-primary w-100">Agregar</button></div>
                        <div class="col-12"><textarea name="descripcion" class="form-control form-control-sm" rows="2" placeholder="Descripción de funciones..."></textarea></div>
                    </div>
                </div>
                </form>
            </div>
            <div class="card-body p-0">
                @forelse($candidato->experiencias as $exp)
                <div class="d-flex align-items-start gap-3 px-3 py-2 border-bottom border-secondary border-opacity-25">
                    <i class="bi bi-briefcase text-warning mt-1"></i>
                    <div class="flex-grow-1">
                        <div class="fw-semibold text-white" style="font-size:.85rem">{{ $exp->cargo }}</div>
                        <div class="text-muted small">{{ $exp->empresa }} · {{ $exp->fecha_inicio->format('M/Y') }} – {{ $exp->actualmente ? 'Presente' : ($exp->fecha_fin?->format('M/Y') ?? '?') }}</div>
                        @if($exp->descripcion)<div class="text-muted" style="font-size:.78rem;margin-top:2px">{{ $exp->descripcion }}</div>@endif
                    </div>
                    <form action="{{ route('candidatos.experiencias.destroy', $exp) }}" method="POST" onsubmit="return confirm('¿Eliminar?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
                @empty
                <div class="text-center py-3 text-muted small">Sin experiencia registrada</div>
                @endforelse
            </div>
        </div>

        <!-- Idiomas & Habilidades -->
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span class="fw-semibold small"><i class="bi bi-translate me-1" style="color:#4f8ef7"></i>Idiomas</span>
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#formIdioma"><i class="bi bi-plus"></i></button>
                    </div>
                    <div class="collapse" id="formIdioma">
                        <form action="{{ route('candidatos.idiomas.store', $candidato) }}" method="POST">
                        @csrf
                        <div class="card-body border-bottom border-secondary border-opacity-25">
                            <div class="row g-2">
                                <div class="col-7"><input type="text" name="idioma" class="form-control form-control-sm" placeholder="Idioma *" required></div>
                                <div class="col-5">
                                    <select name="nivel" class="form-select form-select-sm" required>
                                        <option value="">Nivel...</option>
                                        @foreach(['basico'=>'Básico','intermedio'=>'Intermedio','avanzado'=>'Avanzado','nativo'=>'Nativo'] as $k=>$v)
                                        <option value="{{ $k }}">{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12"><button class="btn btn-sm btn-primary w-100">Agregar</button></div>
                            </div>
                        </div>
                        </form>
                    </div>
                    <div class="card-body p-0">
                        @forelse($candidato->idiomas as $idioma)
                        <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom border-secondary border-opacity-25">
                            <div>
                                <span class="text-white fw-semibold" style="font-size:.83rem">{{ $idioma->idioma }}</span>
                                <span class="badge bg-secondary ms-2">{{ ucfirst($idioma->nivel) }}</span>
                            </div>
                            <form action="{{ route('candidatos.idiomas.destroy', $idioma) }}" method="POST" onsubmit="return confirm('¿Eliminar?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                        @empty
                        <div class="text-center py-3 text-muted small">Sin idiomas</div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span class="fw-semibold small"><i class="bi bi-stars me-1" style="color:#4f8ef7"></i>Habilidades</span>
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#formHab"><i class="bi bi-plus"></i></button>
                    </div>
                    <div class="collapse" id="formHab">
                        <form action="{{ route('candidatos.habilidades.store', $candidato) }}" method="POST">
                        @csrf
                        <div class="card-body border-bottom border-secondary border-opacity-25">
                            <div class="row g-2">
                                <div class="col-8"><input type="text" name="habilidad" class="form-control form-control-sm" placeholder="Habilidad *" required></div>
                                <div class="col-4"><input type="text" name="nivel" class="form-control form-control-sm" placeholder="Nivel"></div>
                                <div class="col-12"><button class="btn btn-sm btn-primary w-100">Agregar</button></div>
                            </div>
                        </div>
                        </form>
                    </div>
                    <div class="card-body p-0">
                        @forelse($candidato->habilidades as $hab)
                        <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom border-secondary border-opacity-25">
                            <div>
                                <span class="text-white fw-semibold" style="font-size:.83rem">{{ $hab->habilidad }}</span>
                                @if($hab->nivel)<span class="badge bg-secondary ms-1">{{ $hab->nivel }}</span>@endif
                            </div>
                            <form action="{{ route('candidatos.habilidades.destroy', $hab) }}" method="POST" onsubmit="return confirm('¿Eliminar?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                        @empty
                        <div class="text-center py-3 text-muted small">Sin habilidades</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Entrevistas del candidato -->
        @if($candidato->entrevistas->count())
        <div class="card mt-3">
            <div class="card-header fw-semibold"><i class="bi bi-chat-dots me-2" style="color:#4f8ef7"></i>Entrevistas Realizadas</div>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead><tr><th>Fecha</th><th>Vacante</th><th>Tipo</th><th>Puntaje</th><th>Resultado</th><th></th></tr></thead>
                    <tbody>
                        @foreach($candidato->entrevistas as $ent)
                        <tr>
                            <td class="text-muted small">{{ $ent->fecha_entrevista->format('d/m/Y H:i') }}</td>
                            <td>{{ $ent->vacante->nombre_puesto }}</td>
                            <td><span class="badge bg-secondary">{{ $ent->tipo_label }}</span></td>
                            <td><strong>{{ $ent->puntaje_total }}</strong>/50</td>
                            <td><span class="badge bg-{{ $ent->resultado_badge }}">{{ ucfirst(str_replace('_',' ',$ent->resultado)) }}</span></td>
                            <td><a href="{{ route('entrevistas.show', $ent) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a></td>
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
