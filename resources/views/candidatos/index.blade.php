@extends('layouts.app')
@section('title', 'Candidatos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0 text-white">Candidatos</h5>
        <small class="text-muted">Registro de aspirantes al proceso de selección</small>
    </div>
    <a href="{{ route('candidatos.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Nuevo Candidato
    </a>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form class="row g-2" method="GET">
            <div class="col-md-5">
                <input type="text" name="buscar" class="form-control form-control-sm" placeholder="Buscar por nombre, cédula o correo..." value="{{ request('buscar') }}">
            </div>
            <div class="col-md-3">
                <select name="estado" class="form-select form-select-sm">
                    <option value="">— Todos los estados —</option>
                    <option value="activo" {{ request('estado')=='activo'?'selected':'' }}>Activo</option>
                    <option value="en_proceso" {{ request('estado')=='en_proceso'?'selected':'' }}>En Proceso</option>
                    <option value="contratado" {{ request('estado')=='contratado'?'selected':'' }}>Contratado</option>
                    <option value="descartado" {{ request('estado')=='descartado'?'selected':'' }}>Descartado</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i> Buscar</button>
                <a href="{{ route('candidatos.index') }}" class="btn btn-sm btn-outline-secondary ms-1"><i class="bi bi-x"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Candidato</th>
                    <th>Cédula</th>
                    <th>Teléfono</th>
                    <th>Aspiración</th>
                    <th>Estado</th>
                    <th>Registrado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($candidatos as $c)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($c->foto)
                                <img src="{{ asset('storage/'.$c->foto) }}" class="rounded-circle" width="34" height="34" style="object-fit:cover">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center bg-secondary" style="width:34px;height:34px;font-size:.8rem">
                                    {{ strtoupper(substr($c->nombre,0,1).substr($c->apellido,0,1)) }}
                                </div>
                            @endif
                            <div>
                                <div class="fw-semibold text-white">{{ $c->nombre_completo }}</div>
                                <div class="text-muted" style="font-size:.75rem">{{ $c->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-muted">{{ $c->cedula }}</td>
                    <td class="text-muted">{{ $c->telefono ?? '—' }}</td>
                    <td>{{ $c->aspiracion_salarial ? '$'.number_format($c->aspiracion_salarial,2) : '—' }}</td>
                    <td>
                        @php
                            $colors = ['activo'=>'success','en_proceso'=>'warning','contratado'=>'primary','descartado'=>'danger','archivado'=>'secondary'];
                            $labels = ['activo'=>'Activo','en_proceso'=>'En Proceso','contratado'=>'Contratado','descartado'=>'Descartado','archivado'=>'Archivado'];
                        @endphp
                        <span class="badge bg-{{ $colors[$c->estado] ?? 'secondary' }}">{{ $labels[$c->estado] ?? $c->estado }}</span>
                    </td>
                    <td class="text-muted small">{{ $c->created_at->format('d/m/Y') }}</td>
                    <td class="text-end">
                        <a href="{{ route('candidatos.show', $c) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('candidatos.edit', $c) }}" class="btn btn-sm btn-outline-warning ms-1"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('candidatos.destroy', $c) }}" method="POST" class="d-inline ms-1"
                              onsubmit="return confirm('¿Eliminar candidato?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">No se encontraron candidatos.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($candidatos->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center py-2">
        <small class="text-muted">{{ $candidatos->total() }} candidatos</small>
        {{ $candidatos->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
