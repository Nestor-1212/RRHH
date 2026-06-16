@extends('layouts.app')
@section('title', 'Editar Candidato')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('candidatos.show', $candidato) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h5 class="fw-bold mb-0 text-white">Editar Candidato</h5>
        <small class="text-muted">{{ $candidato->nombre_completo }}</small>
    </div>
</div>

<form action="{{ route('candidatos.update', $candidato) }}" method="POST" enctype="multipart/form-data">
@csrf @method('PUT')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header fw-semibold"><i class="bi bi-person me-2" style="color:#4f8ef7"></i>Datos Personales</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nombre *</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $candidato->nombre) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Apellido *</label>
                        <input type="text" name="apellido" class="form-control" value="{{ old('apellido', $candidato->apellido) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Cédula *</label>
                        <input type="text" name="cedula" class="form-control" value="{{ old('cedula', $candidato->cedula) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento', $candidato->fecha_nacimiento?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $candidato->telefono) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Correo</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $candidato->email) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Aspiración Salarial</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary">$</span>
                            <input type="number" name="aspiracion_salarial" class="form-control" step="0.01" value="{{ old('aspiracion_salarial', $candidato->aspiracion_salarial) }}">
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Dirección</label>
                        <textarea name="direccion" class="form-control" rows="2">{{ old('direccion', $candidato->direccion) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Notas</label>
                        <textarea name="notas" class="form-control" rows="2">{{ old('notas', $candidato->notas) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header fw-semibold"><i class="bi bi-image me-2" style="color:#4f8ef7"></i>Foto y CV</div>
            <div class="card-body">
                @if($candidato->foto)
                <div class="text-center mb-2">
                    <img src="{{ asset('storage/'.$candidato->foto) }}" class="rounded" width="80" height="80" style="object-fit:cover">
                    <div class="text-muted small mt-1">Foto actual</div>
                </div>
                @endif
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Nueva Foto</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                </div>
                @if($candidato->hoja_vida)
                <div class="mb-2"><a href="{{ asset('storage/'.$candidato->hoja_vida) }}" target="_blank" class="btn btn-sm btn-outline-info w-100"><i class="bi bi-file-pdf me-1"></i>Ver CV actual</a></div>
                @endif
                <div>
                    <label class="form-label small fw-semibold">Nueva Hoja de Vida (PDF)</label>
                    <input type="file" name="hoja_vida" class="form-control" accept=".pdf">
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header fw-semibold">Estado</div>
            <div class="card-body">
                <select name="estado" class="form-select">
                    @foreach(['activo','en_proceso','contratado','descartado','archivado'] as $e)
                    <option value="{{ $e }}" {{ $candidato->estado==$e?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$e)) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Actualizar</button>
        <a href="{{ route('candidatos.show', $candidato) }}" class="btn btn-outline-secondary ms-2">Cancelar</a>
    </div>
</div>
</form>
@endsection
