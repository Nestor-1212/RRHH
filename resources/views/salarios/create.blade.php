@extends('layouts.app')
@section('title', 'Cambio Salarial')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('empleados.show', $empleado) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h5 class="fw-bold mb-0 text-white">Registrar Cambio Salarial</h5>
        <small class="text-muted">{{ $empleado->nombre_completo }} · Salario actual: <strong>${{ number_format($empleado->salario,2) }}</strong></small>
    </div>
</div>
<div class="row"><div class="col-lg-6">
<form action="{{ route('salarios.store', $empleado) }}" method="POST">
@csrf
<div class="card">
    <div class="card-header fw-semibold"><i class="bi bi-cash-coin me-2" style="color:#4f8ef7"></i>Datos del Cambio</div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Tipo *</label>
                <select name="tipo" class="form-select" required id="tipoSelect" onchange="updateLabel()">
                    <option value="aumento">Aumento</option>
                    <option value="bonificacion">Bonificación</option>
                    <option value="descuento">Descuento</option>
                    <option value="ajuste">Ajuste (monto fijo)</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold" id="montoLabel">Monto del Aumento *</label>
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary">$</span>
                    <input type="number" name="monto" class="form-control" step="0.01" min="0.01" required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Fecha *</label>
                <input type="date" name="fecha" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Motivo</label>
                <textarea name="motivo" class="form-control" rows="3" placeholder="Razón del cambio salarial..."></textarea>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Registrar</button>
        <a href="{{ route('empleados.show', $empleado) }}" class="btn btn-outline-secondary ms-2">Cancelar</a>
    </div>
</div>
</form>
</div></div>
@push('scripts')
<script>
const labels = {aumento:'Monto del Aumento *',bonificacion:'Monto de la Bonificación *',descuento:'Monto del Descuento *',ajuste:'Nuevo Salario Fijo *'};
function updateLabel(){document.getElementById('montoLabel').textContent=labels[document.getElementById('tipoSelect').value];}
</script>
@endpush
@endsection
