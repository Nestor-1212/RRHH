@extends('layouts.app')
@section('title', 'Editar Empleado')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('empleados.show', $empleado) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0 text-white">Editar Empleado — {{ $empleado->nombre_completo }}</h5>
</div>
<form action="{{ route('empleados.update', $empleado) }}" method="POST" enctype="multipart/form-data">
@csrf @method('PUT')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header fw-semibold"><i class="bi bi-person me-2" style="color:#4f8ef7"></i>Datos Personales</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label small fw-semibold">Nombre *</label><input type="text" name="nombre" class="form-control" value="{{ old('nombre',$empleado->nombre) }}" required></div>
                    <div class="col-md-6"><label class="form-label small fw-semibold">Apellido *</label><input type="text" name="apellido" class="form-control" value="{{ old('apellido',$empleado->apellido) }}" required></div>
                    <div class="col-md-4"><label class="form-label small fw-semibold">Cédula *</label><input type="text" name="cedula" class="form-control" value="{{ old('cedula',$empleado->cedula) }}" required></div>
                    <div class="col-md-4"><label class="form-label small fw-semibold">Nacimiento</label><input type="date" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento',$empleado->fecha_nacimiento?->format('Y-m-d')) }}"></div>
                    <div class="col-md-4"><label class="form-label small fw-semibold">Teléfono</label><input type="text" name="telefono" class="form-control" value="{{ old('telefono',$empleado->telefono) }}"></div>
                    <div class="col-md-6"><label class="form-label small fw-semibold">Correo</label><input type="email" name="email" class="form-control" value="{{ old('email',$empleado->email) }}"></div>
                    <div class="col-12"><label class="form-label small fw-semibold">Dirección</label><textarea name="direccion" class="form-control" rows="2">{{ old('direccion',$empleado->direccion) }}</textarea></div>
                    <div class="col-md-6"><label class="form-label small fw-semibold">Contacto Emergencia</label><input type="text" name="contacto_emergencia" class="form-control" value="{{ old('contacto_emergencia',$empleado->contacto_emergencia) }}"></div>
                    <div class="col-md-6"><label class="form-label small fw-semibold">Teléfono Emergencia</label><input type="text" name="telefono_emergencia" class="form-control" value="{{ old('telefono_emergencia',$empleado->telefono_emergencia) }}"></div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header fw-semibold"><i class="bi bi-building me-2" style="color:#4f8ef7"></i>Información Laboral</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label small fw-semibold">Fecha Ingreso *</label><input type="date" name="fecha_ingreso" class="form-control" value="{{ old('fecha_ingreso',$empleado->fecha_ingreso->format('Y-m-d')) }}" required></div>
                    <div class="col-md-8"><label class="form-label small fw-semibold">Cargo *</label><input type="text" name="cargo" class="form-control" value="{{ old('cargo',$empleado->cargo) }}" required></div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Departamento *</label>
                        <select name="departamento_id" class="form-select" required>
                            @foreach($departamentos as $d)
                            <option value="{{ $d->id }}" {{ $empleado->departamento_id==$d->id?'selected':'' }}>{{ $d->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Jefe Inmediato</label>
                        <select name="jefe_id" class="form-select">
                            <option value="">— Ninguno —</option>
                            @foreach($jefes as $j)
                            <option value="{{ $j->id }}" {{ $empleado->jefe_id==$j->id?'selected':'' }}>{{ $j->nombre_completo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Tipo Contrato *</label>
                        <select name="tipo_contrato" class="form-select" required>
                            @foreach(['indefinido','definido','servicios','pasantia'] as $t)
                            <option value="{{ $t }}" {{ $empleado->tipo_contrato==$t?'selected':'' }}>{{ ucfirst($t) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Salario *</label>
                        <div class="input-group"><span class="input-group-text bg-dark border-secondary">$</span>
                        <input type="number" name="salario" class="form-control" step="0.01" value="{{ old('salario',$empleado->salario) }}" required></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Estado</label>
                        <select name="estado" class="form-select">
                            @foreach(['activo','inactivo','retirado'] as $e)
                            <option value="{{ $e }}" {{ $empleado->estado==$e?'selected':'' }}>{{ ucfirst($e) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Jornada *</label>
                        <select name="jornada" class="form-select" required>
                            @foreach(['completa','parcial','mixta','nocturna'] as $j)
                            <option value="{{ $j }}" {{ $empleado->jornada==$j?'selected':'' }}>{{ ucfirst($j) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-8"><label class="form-label small fw-semibold">Horario</label><input type="text" name="horario" class="form-control" value="{{ old('horario',$empleado->horario) }}"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header fw-semibold">Foto</div>
            <div class="card-body">
                @if($empleado->foto)
                <div class="text-center mb-2"><img src="{{ asset('storage/'.$empleado->foto) }}" class="rounded" width="80" height="80" style="object-fit:cover"></div>
                @endif
                <input type="file" name="foto" class="form-control" accept="image/*">
            </div>
        </div>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Actualizar</button>
        <a href="{{ route('empleados.show', $empleado) }}" class="btn btn-outline-secondary ms-2">Cancelar</a>
    </div>
</div>
</form>
@endsection
