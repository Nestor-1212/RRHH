@extends('layouts.app')
@section('title', 'Empleados')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0 text-white">Empleados</h5>
        <small class="text-muted">Expedientes del personal de la empresa</small>
    </div>
    <a href="{{ route('empleados.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Nuevo Empleado</a>
</div>

<div class="card mb-3"><div class="card-body py-2">
    <form class="row g-2" method="GET">
        <div class="col-md-4"><input type="text" name="buscar" class="form-control form-control-sm" placeholder="Nombre, cédula o código..." value="{{ request('buscar') }}"></div>
        <div class="col-md-3">
            <select name="departamento_id" class="form-select form-select-sm">
                <option value="">— Departamento —</option>
                @foreach($departamentos as $d)
                <option value="{{ $d->id }}" {{ request('departamento_id')==$d->id?'selected':'' }}>{{ $d->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="estado" class="form-select form-select-sm">
                <option value="">— Estado —</option>
                <option value="activo" {{ request('estado')=='activo'?'selected':'' }}>Activo</option>
                <option value="inactivo" {{ request('estado')=='inactivo'?'selected':'' }}>Inactivo</option>
                <option value="retirado" {{ request('estado')=='retirado'?'selected':'' }}>Retirado</option>
            </select>
        </div>
        <div class="col-auto">
            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>
            <a href="{{ route('empleados.index') }}" class="btn btn-sm btn-outline-secondary ms-1"><i class="bi bi-x"></i></a>
        </div>
    </form>
</div></div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Empleado</th><th>Código</th><th>Cargo</th><th>Departamento</th><th>Salario</th><th>Ingreso</th><th>Estado</th><th class="text-end">Acciones</th></tr></thead>
            <tbody>
                @forelse($empleados as $e)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($e->foto)
                                <img src="{{ asset('storage/'.$e->foto) }}" class="rounded-circle" width="34" height="34" style="object-fit:cover">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:rgba(79,142,247,.2);color:#4f8ef7;font-size:.8rem;font-weight:700">
                                    {{ strtoupper(substr($e->nombre,0,1).substr($e->apellido,0,1)) }}
                                </div>
                            @endif
                            <div>
                                <div class="fw-semibold text-white" style="font-size:.85rem">{{ $e->nombre_completo }}</div>
                                <div class="text-muted" style="font-size:.73rem">{{ $e->email ?? $e->cedula }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-muted small">{{ $e->codigo_empleado }}</td>
                    <td class="text-muted small">{{ $e->cargo }}</td>
                    <td class="text-muted small">{{ $e->departamento->nombre }}</td>
                    <td class="text-success small fw-semibold">${{ number_format($e->salario,2) }}</td>
                    <td class="text-muted small">{{ $e->fecha_ingreso->format('d/m/Y') }}</td>
                    <td><span class="badge bg-{{ $e->estado_badge }}">{{ ucfirst($e->estado) }}</span></td>
                    <td class="text-end">
                        <a href="{{ route('empleados.show', $e) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('empleados.edit', $e) }}" class="btn btn-sm btn-outline-warning ms-1"><i class="bi bi-pencil"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">No se encontraron empleados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($empleados->hasPages())
    <div class="card-footer py-2 d-flex justify-content-between align-items-center">
        <small class="text-muted">{{ $empleados->total() }} empleados</small>
        {{ $empleados->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
