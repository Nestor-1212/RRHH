@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
    .kpi-card {
        border: none;
        border-radius: 14px;
        padding: 1.3rem 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,.07);
        transition: transform .15s, box-shadow .15s;
    }
    .kpi-card:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,.1); }
    .kpi-card .kpi-icon {
        width: 52px; height: 52px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
    }
    .kpi-card .kpi-val { font-size: 2rem; font-weight: 700; line-height: 1.1; color: #1f2937; }
    .kpi-card .kpi-lbl { font-size: .75rem; color: #6b7280; margin-top: 3px; text-transform: uppercase; letter-spacing: .6px; }
    .kpi-card .kpi-sub { font-size: .78rem; margin-top: .4rem; }

    .chart-card { border-radius: 14px; border: 1px solid #e5e7eb; box-shadow: 0 1px 5px rgba(0,0,0,.05); }
    .chart-card .card-header { font-size: .82rem; font-weight: 700; background: var(--c1-light); border-bottom: 2px solid var(--c3); color: var(--c1); }

    .eval-row { transition: background .1s; }
    .eval-row:hover { background: var(--c4-light); }
    .eval-star { color: var(--c3); font-size: .9rem; }

    .dept-bar-wrap { display: flex; align-items: center; gap: .7rem; margin-bottom: .55rem; }
    .dept-bar-wrap .dept-name { font-size: .78rem; color: #374151; min-width: 110px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .dept-bar-bg { flex: 1; background: #f3f4f6; border-radius: 20px; height: 8px; overflow: hidden; }
    .dept-bar-fill { height: 8px; border-radius: 20px; }
    .dept-bar-count { font-size: .75rem; font-weight: 600; color: #374151; min-width: 24px; text-align: right; }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="mb-0 fw-bold" style="color:var(--c1)">
            <i class="bi bi-grid-1x2-fill me-2"></i>Dashboard
        </h5>
        <p class="text-muted small mb-0">
            {{ \Carbon\Carbon::now()->translatedFormat('l, d \d\e F \d\e Y') }}
        </p>
    </div>
    @can('create', \App\Models\Empleado::class)
    <a href="{{ route('empleados.create') }}" class="btn btn-sm btn-primary">
        <i class="bi bi-person-plus me-1"></i>Nuevo Empleado
    </a>
    @endcan
</div>

{{-- ── KPI Row ── --}}
<div class="row g-3 mb-4">

    <div class="col-6 col-md-3">
        <div class="kpi-card" style="background:var(--c5)">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <div class="kpi-val" style="color:var(--c1)">{{ $totalActivos }}</div>
                    <div class="kpi-lbl">Empleados Activos</div>
                    <div class="kpi-sub text-success">
                        <i class="bi bi-arrow-up-short"></i>+{{ $nuevosIngresos }} este mes
                    </div>
                </div>
                <div class="kpi-icon" style="background:var(--c1-light);color:var(--c1)">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="kpi-card" style="background:var(--c5)">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <div class="kpi-val" style="color:var(--c4)">{{ $tasaAsistencia }}%</div>
                    <div class="kpi-lbl">Asistencia (mes)</div>
                    <div class="kpi-sub" style="color:var(--c4)">
                        <i class="bi bi-calendar-check me-1"></i>Tasa actual
                    </div>
                </div>
                <div class="kpi-icon" style="background:var(--c4-light);color:var(--c4)">
                    <i class="bi bi-calendar-check-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="kpi-card" style="background:var(--c5)">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <div class="kpi-val" style="color:#d97706">{{ $vacantesAbiertas }}</div>
                    <div class="kpi-lbl">Vacantes Abiertas</div>
                    <div class="kpi-sub text-muted">
                        <i class="bi bi-person-check me-1"></i>{{ $candidatosEnProceso }} en proceso
                    </div>
                </div>
                <div class="kpi-icon" style="background:#fef3c7;color:#d97706">
                    <i class="bi bi-briefcase-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="kpi-card" style="background:var(--c5)">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <div class="kpi-val" style="color:{{ $amonestacionesMes > 0 ? '#dc2626' : '#16a34a' }}">{{ $amonestacionesMes }}</div>
                    <div class="kpi-lbl">Amonestaciones (mes)</div>
                    <div class="kpi-sub text-muted">
                        <i class="bi bi-person-x me-1"></i>{{ $totalRetirados }} retirados total
                    </div>
                </div>
                <div class="kpi-icon" style="background:#fee2e2;color:#dc2626">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Gráficos ── --}}
<div class="row g-3 mb-4">

    <div class="col-12 col-lg-7">
        <div class="card chart-card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-bar-chart-fill me-2"></i>Rotación Anual {{ now()->year }}</span>
                <span class="badge" style="background:var(--c1-light);color:var(--c1);font-size:.7rem">Ingresos vs Salidas</span>
            </div>
            <div class="card-body p-3">
                <canvas id="chartRotacion" height="120"></canvas>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="card chart-card h-100">
            <div class="card-header">
                <i class="bi bi-diagram-3-fill me-2"></i>Headcount por Departamento
            </div>
            <div class="card-body p-3">
                @php $maxHc = $headcount->max('total') ?: 1; @endphp
                @forelse($headcount as $dept)
                    <div class="dept-bar-wrap">
                        <span class="dept-name" title="{{ $dept->nombre }}">{{ $dept->nombre }}</span>
                        <div class="dept-bar-bg">
                            <div class="dept-bar-fill"
                                 style="width:{{ round(($dept->total / $maxHc) * 100) }}%;
                                        background:{{ $loop->index % 2 === 0 ? 'var(--c1)' : 'var(--c4)' }}">
                            </div>
                        </div>
                        <span class="dept-bar-count">{{ $dept->total }}</span>
                    </div>
                @empty
                    <p class="text-muted small text-center mt-4"><i class="bi bi-inbox d-block fs-3 mb-2"></i>Sin datos</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ── Tablas ── --}}
<div class="row g-3">

    <div class="col-12 col-lg-6">
        <div class="card chart-card">
            <div class="card-header">
                <i class="bi bi-chat-dots-fill me-2"></i>Entrevistas Recientes
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0 small">
                    <thead>
                        <tr>
                            <th class="ps-3">Candidato</th>
                            <th>Vacante</th>
                            <th>Puntaje</th>
                            <th class="pe-3">Resultado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entrevistasRecientes as $e)
                        <tr class="eval-row">
                            <td class="ps-3 fw-semibold">{{ $e->candidato?->nombre_completo ?? '—' }}</td>
                            <td class="text-muted">{{ Str::limit($e->vacante?->titulo ?? '—', 22) }}</td>
                            <td>
                                @if($e->puntaje)
                                    <span class="fw-semibold">{{ $e->puntaje }}<span class="text-muted fw-normal">/100</span></span>
                                @else —
                                @endif
                            </td>
                            <td class="pe-3">
                                @php
                                    $rc = match($e->resultado ?? '') {
                                        'aprobado'  => ['success','check-circle-fill'],
                                        'rechazado' => ['danger', 'x-circle-fill'],
                                        'pendiente' => ['warning','clock-fill'],
                                        default     => ['secondary','dash-circle'],
                                    };
                                @endphp
                                <span class="badge bg-{{ $rc[0] }}">
                                    <i class="bi bi-{{ $rc[1] }} me-1"></i>{{ ucfirst($e->resultado ?? 'pendiente') }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-4 d-block mb-2"></i>Sin entrevistas registradas
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white border-0 pb-2">
                <a href="{{ route('entrevistas.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                    Ver todas <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="card chart-card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-star-fill me-2" style="color:var(--c3)"></i>Desempeño Excelente</span>
                <a href="{{ route('evaluaciones.index') }}" class="btn btn-sm btn-outline-secondary">Ver todas</a>
            </div>
            <div class="card-body p-0">
                @forelse($topEvaluaciones as $ev)
                <div class="d-flex align-items-center gap-3 px-3 py-2 border-bottom eval-row">
                    <div class="flex-shrink-0 d-flex align-items-center justify-content-center"
                         style="width:38px;height:38px;border-radius:10px;background:var(--c4-light);color:var(--c4)">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div class="flex-fill overflow-hidden">
                        <div class="fw-semibold text-truncate small">{{ $ev->empleado?->nombre_completo ?? '—' }}</div>
                        <div class="text-muted" style="font-size:.72rem">
                            {{ $ev->empleado?->departamento?->nombre ?? '' }}
                            @if($ev->periodo) &bull; {{ $ev->periodo }} @endif
                        </div>
                    </div>
                    <div class="text-end flex-shrink-0">
                        <div class="fw-bold small" style="color:var(--c1)">{{ $ev->puntaje_total }}<span class="text-muted fw-normal">/100</span></div>
                        <div>@for($s=0;$s<5;$s++)<i class="bi bi-star-fill eval-star"></i>@endfor</div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-5">
                    <i class="bi bi-clipboard-x fs-3 d-block mb-2"></i>Sin evaluaciones de excelencia aún
                </div>
                @endforelse
            </div>

            @if($evalPorDepto->isNotEmpty())
            <div class="card-footer bg-white border-0 py-3 px-3">
                <div class="text-uppercase fw-semibold text-muted mb-2" style="font-size:.68rem;letter-spacing:.6px">
                    Promedio evaluación por departamento
                </div>
                @foreach($evalPorDepto as $dep)
                <div class="dept-bar-wrap">
                    <span class="dept-name" title="{{ $dep->nombre }}">{{ $dep->nombre }}</span>
                    <div class="dept-bar-bg">
                        <div class="dept-bar-fill" style="width:{{ $dep->promedio }}%;background:var(--c3)"></div>
                    </div>
                    <span class="dept-bar-count" style="color:var(--c1)">{{ $dep->promedio }}</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    const meses   = @json($meses);
    const ingrs   = @json($ingresos);
    const sals    = @json($salidas);

    const ctx = document.getElementById('chartRotacion');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: meses,
            datasets: [
                {
                    label: 'Ingresos',
                    data: ingrs,
                    backgroundColor: 'rgba(226,128,0,.8)',
                    borderColor: '#e28000',
                    borderWidth: 1,
                    borderRadius: 5,
                },
                {
                    label: 'Salidas',
                    data: sals,
                    backgroundColor: 'rgba(0,128,128,.65)',
                    borderColor: '#008080',
                    borderWidth: 1,
                    borderRadius: 5,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'top', labels: { font: { size: 11 } } },
                tooltip: { mode: 'index', intersect: false }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11 } } },
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, font: { size: 11 } },
                    grid: { color: 'rgba(0,0,0,.06)' }
                }
            }
        }
    });
}());
</script>
@endpush
