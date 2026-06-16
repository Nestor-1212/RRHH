@extends('layouts.app')
@section('title', 'Salidas de Personal')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0 text-white">Salidas de Personal</h5>
        <small class="text-muted">Registro de renuncias, despidos y retiros</small>
    </div>
    <a href="{{ route('salidas.create') }}" class="btn btn-danger"><i class="bi bi-door-open me-1"></i> Registrar Salida</a>
</div>

<div class="card mb-3"><div class="card-body py-2">
    <form class="row g-2" method="GET">
        <div class="col-md-3">
            <select name="tipo" class="form-select form-select-sm">
                <option value="">— Tipo —</option>
                @foreach(['renuncia','despido','finalizacion_contrato','jubilacion','otro'] as $t)
                <option value="{{ $t }}" {{ request('tipo')==$t?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$t)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>
            <a href="{{ route('salidas.index') }}" class="btn btn-sm btn-outline-secondary ms-1"><i class="bi bi-x"></i></a>
        </div>
    </form>
</div></div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Empleado</th><th>Fecha Salida</th><th>Tipo</th><th>Último Cargo</th><th>Último Salario</th><th class="text-end">Ver</th></tr></thead>
            <tbody>
                @forelse($salidas as $s)
                <tr>
                    <td class="fw-semibold text-white" style="font-size:.85rem">{{ $s->empleado->nombre_completo }}</td>
                    <td class="text-muted small">{{ $s->fecha_salida->format('d/m/Y') }}</td>
                    <td><span class="badge bg-{{ $s->tipo_badge }}">{{ $s->tipo_label }}</span></td>
                    <td class="text-muted small">{{ $s->ultimo_cargo }}</td>
                    <td class="text-success">${{ number_format($s->ultimo_salario,2) }}</td>
                    <td class="text-end"><a href="{{ route('salidas.show', $s) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a></td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">Sin salidas registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($salidas->hasPages())<div class="card-footer py-2">{{ $salidas->links('pagination::bootstrap-5') }}</div>@endif
</div>
@endsection
