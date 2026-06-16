@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0 text-white">Panel de Control</h5>
        <small class="text-muted">Resumen general del sistema de RRHH</small>
    </div>
    <span class="text-muted small"><i class="bi bi-calendar3 me-1"></i>{{ now()->translatedFormat('d \d\e F, Y') }}</span>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-2">
        <div class="stat-card">
            <div class="stat-icon mb-2" style="background:rgba(25,135,84,.15)">
                <i class="bi bi-person-check" style="color:#198754"></i>
            </div>
            <div class="stat-value text-white">{{ $totalEmpleados }}</div>
            <div class="stat-label">Empleados Activos</div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="stat-card">
            <div class="stat-icon mb-2" style="background:rgba(79,142,247,.15)">
                <i class="bi bi-person-plus" style="color:#4f8ef7"></i>
            </div>
            <div class="stat-value text-white">{{ $nuevosIngresos }}</div>
            <div class="stat-label">Ingresos este mes</div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="stat-card">
            <div class="stat-icon mb-2" style="background:rgba(220,53,69,.15)">
                <i class="bi bi-person-dash" style="color:#dc3545"></i>
            </div>
            <div class="stat-value text-white">{{ $totalRetirados }}</div>
            <div class="stat-label">Empleados Retirados</div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="stat-card">
            <div class="stat-icon mb-2" style="background:rgba(255,193,7,.15)">
                <i class="bi bi-briefcase" style="color:#ffc107"></i>
            </div>
            <div class="stat-value text-white">{{ $vacantesAbiertas }}</div>
            <div class="stat-label">Vacantes Abiertas</div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="stat-card">
            <div class="stat-icon mb-2" style="background:rgba(13,202,240,.15)">
                <i class="bi bi-people" style="color:#0dcaf0"></i>
            </div>
            <div class="stat-value text-white">{{ $candidatosEnProceso }}</div>
            <div class="stat-label">Candidatos en Proceso</div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="stat-card">
            <div class="stat-icon mb-2" style="background:rgba(255,99,71,.15)">
                <i class="bi bi-exclamation-triangle" style="color:#ff6347"></i>
            </div>
            <div class="stat-value text-white">{{ $amonestacionesMes }}</div>
            <div class="stat-label">Amonestaciones mes</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Gráfico Rotación -->
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center gap-2 py-3">
                <i class="bi bi-bar-chart-line text-accent" style="color:#4f8ef7"></i>
                <span class="fw-semibold">Rotación Laboral {{ now()->year }}</span>
            </div>
            <div class="card-body">
                <canvas id="rotacionChart" height="90"></canvas>
            </div>
        </div>
    </div>

    <!-- Últimas Entrevistas -->
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between py-3">
                <span class="fw-semibold"><i class="bi bi-chat-dots me-2" style="color:#4f8ef7"></i>Últimas Entrevistas</span>
                <a href="{{ route('entrevistas.index') }}" class="btn btn-sm btn-outline-secondary">Ver todo</a>
            </div>
            <div class="card-body p-0">
                @forelse($entrevistasRecientes as $e)
                <div class="d-flex align-items-start gap-3 px-3 py-2 border-bottom border-secondary border-opacity-25">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:36px;height:36px;background:rgba(79,142,247,.15)">
                        <i class="bi bi-person" style="color:#4f8ef7"></i>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="fw-semibold text-truncate text-white" style="font-size:.85rem">{{ $e->candidato->nombre_completo }}</div>
                        <div class="text-muted" style="font-size:.75rem">{{ $e->vacante->nombre_puesto }} — {{ $e->tipo_label }}</div>
                    </div>
                    <span class="badge bg-{{ $e->resultado_badge }} badge-custom flex-shrink-0">
                        {{ ucfirst(str_replace('_',' ',$e->resultado)) }}
                    </span>
                </div>
                @empty
                <div class="text-center py-4 text-muted small">No hay entrevistas recientes</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const ctx = document.getElementById('rotacionChart');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($meses) !!},
        datasets: [{
            label: 'Ingresos',
            data: {!! json_encode($ingresos) !!},
            backgroundColor: 'rgba(79,142,247,.7)',
            borderRadius: 4,
        }, {
            label: 'Salidas',
            data: {!! json_encode($salidas) !!},
            backgroundColor: 'rgba(220,53,69,.6)',
            borderRadius: 4,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { labels: { color: '#9ca3af', font: { size: 11 } } } },
        scales: {
            x: { ticks: { color: '#9ca3af' }, grid: { color: '#2a2d35' } },
            y: { ticks: { color: '#9ca3af', stepSize: 1 }, grid: { color: '#2a2d35' } }
        }
    }
});
</script>
@endpush
@endsection
