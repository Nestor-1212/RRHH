@extends('layouts.app')
@section('title', 'Registrar Salida')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('salidas.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0 text-white">Registrar Salida de Empleado</h5>
</div>
<div class="alert alert-warning d-flex align-items-center gap-2">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <span>Esta acción marcará al empleado como <strong>Retirado</strong> y no podrá ser revertida fácilmente.</span>
</div>
<div class="row"><div class="col-lg-8">
<form action="{{ route('salidas.store') }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="card mb-3">
    <div class="card-header fw-semibold"><i class="bi bi-door-open me-2 text-danger"></i>Datos de la Salida</div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Empleado *</label>
                <select name="empleado_id" class="form-select" required>
                    <option value="">— Seleccionar —</option>
                    @foreach($empleados as $e)
                    <option value="{{ $e->id }}" {{ ($empleadoId==$e->id)?'selected':'' }}>{{ $e->nombre_completo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Fecha de Salida *</label>
                <input type="date" name="fecha_salida" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Tipo *</label>
                <select name="tipo" class="form-select" required>
                    <option value="renuncia">Renuncia</option>
                    <option value="despido">Despido</option>
                    <option value="finalizacion_contrato">Fin de Contrato</option>
                    <option value="jubilacion">Jubilación</option>
                    <option value="otro">Otro</option>
                </select>
            </div>
            <div class="col-md-8">
                <label class="form-label small fw-semibold">Último Cargo *</label>
                <input type="text" name="ultimo_cargo" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Último Salario *</label>
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary">$</span>
                    <input type="number" name="ultimo_salario" class="form-control" step="0.01" min="0" required>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Motivo</label>
                <textarea name="motivo" class="form-control" rows="3"></textarea>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header fw-semibold"><i class="bi bi-paperclip me-2" style="color:#4f8ef7"></i>Documentos</div>
    <div class="card-body">
        <div id="docContainer">
            <div class="row g-2 mb-2 doc-row">
                <div class="col-md-4">
                    <select name="tipo_archivo[]" class="form-select form-select-sm">
                        <option value="carta_renuncia">Carta de Renuncia</option>
                        <option value="carta_despido">Carta de Despido</option>
                        <option value="acta_entrega">Acta de Entrega</option>
                        <option value="liquidacion">Liquidación</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>
                <div class="col-md-8"><input type="file" name="archivos[]" class="form-control form-control-sm"></div>
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary mt-1" onclick="addDoc()"><i class="bi bi-plus-lg"></i></button>
    </div>
</div>

<button type="submit" class="btn btn-danger" onclick="return confirm('¿Confirmar salida del empleado?')">
    <i class="bi bi-door-open me-1"></i> Confirmar Salida
</button>
<a href="{{ route('salidas.index') }}" class="btn btn-outline-secondary ms-2">Cancelar</a>
</form>
</div></div>
@push('scripts')
<script>
function addDoc(){const t=document.querySelector('.doc-row').cloneNode(true);t.querySelector('input[type="file"]').value='';document.getElementById('docContainer').appendChild(t);}
</script>
@endpush
@endsection
