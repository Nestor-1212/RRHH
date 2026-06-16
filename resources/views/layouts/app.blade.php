<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistema RRHH') — RRHH</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --sidebar-w: 260px;
            --topbar-h: 58px;
            --c1: #e28000;   /* naranja oscuro */
            --c2: #ff9800;   /* naranja medio  */
            --c3: #ffc340;   /* dorado          */
            --c4: #008080;   /* teal            */
            --c5: #FFFFFF;   /* blanco          */
            --c1-light: #fff3e0;
            --c4-light: #e0f2f2;
        }
        body { background: #f5f6f8; color: #1f2937; font-size: .9rem; }

        /* ── Sidebar ── */
        #sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: linear-gradient(180deg, var(--c1) 0%, var(--c2) 100%);
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            overflow-y: auto;
            transition: transform .25s ease;
        }
        #sidebar .brand {
            padding: 1.1rem 1.2rem;
            border-bottom: 1px solid rgba(255,255,255,.25);
            font-weight: 700;
            font-size: 1rem;
            color: var(--c5);
            letter-spacing: .5px;
        }
        #sidebar .brand i { color: var(--c3); }
        #sidebar .nav-label {
            font-size: .68rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,.55);
            padding: .75rem 1.2rem .25rem;
        }
        #sidebar .nav-link {
            color: rgba(255,255,255,.88);
            padding: .45rem 1.2rem;
            border-radius: 6px;
            margin: 1px 8px;
            display: flex;
            align-items: center;
            gap: .6rem;
            transition: background .15s, color .15s;
        }
        #sidebar .nav-link:hover {
            background: rgba(255,255,255,.18);
            color: var(--c3);
        }
        #sidebar .nav-link.active {
            background: var(--c4);
            color: var(--c5);
            font-weight: 600;
        }
        #sidebar .nav-link.active i { color: var(--c3); }
        #sidebar .nav-link i { font-size: 1rem; width: 18px; text-align: center; }

        /* ── Topbar ── */
        #topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: var(--c5);
            border-bottom: 3px solid var(--c2);
            z-index: 999;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            gap: 1rem;
        }
        #topbar .fw-semibold { color: var(--c1); }

        /* ── Content ── */
        #main-content {
            margin-left: var(--sidebar-w);
            margin-top: var(--topbar-h);
            padding: 1.5rem;
            min-height: calc(100vh - var(--topbar-h));
        }

        /* ── Cards ── */
        .card { background: var(--c5); border: 1px solid #e5e7eb; border-radius: 10px; box-shadow: 0 1px 4px rgba(0,0,0,.05); }
        .card-header { background: var(--c1-light); border-bottom: 2px solid var(--c3); color: var(--c1); font-weight: 700; }

        /* ── Tables ── */
        .table { --bs-table-bg: var(--c5); --bs-table-striped-bg: #fffbf5; --bs-table-hover-bg: var(--c4-light); }
        .table thead th { border-bottom: 2px solid var(--c3); background: var(--c1-light); color: var(--c1); font-weight: 700; font-size: .75rem; text-transform: uppercase; letter-spacing: .8px; }

        /* ── Stat cards ── */
        .badge-custom { font-size: .72rem; }
        .stat-card { background: var(--c5); border: 1px solid #e5e7eb; border-radius: 12px; padding: 1.2rem 1.4rem; box-shadow: 0 1px 4px rgba(0,0,0,.06); }
        .stat-card .stat-icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; }
        .stat-card .stat-value { font-size: 1.8rem; font-weight: 700; line-height: 1; color: #1f2937; }
        .stat-card .stat-label { font-size: .78rem; color: #6b7280; margin-top: 2px; }

        /* ── Forms ── */
        .form-control, .form-select {
            background: var(--c5);
            border-color: #d1d5db;
            color: #1f2937;
        }
        .form-control:focus, .form-select:focus {
            background: var(--c5);
            border-color: var(--c4);
            color: #1f2937;
            box-shadow: 0 0 0 0.2rem rgba(0,128,128,.2);
        }
        .form-control::placeholder { color: #9ca3af; }
        .form-label { color: #374151; font-weight: 500; }

        /* ── Buttons ── */
        .btn-primary { background: var(--c4); border-color: var(--c4); color: var(--c5); font-weight: 600; }
        .btn-primary:hover { background: #006666; border-color: #006666; color: var(--c5); }

        /* ── Timeline ── */
        .timeline { position: relative; padding-left: 2.5rem; }
        .timeline::before { content: ''; position: absolute; left: .9rem; top: 0; bottom: 0; width: 2px; background: linear-gradient(180deg, var(--c2), var(--c4)); }
        .timeline-item { position: relative; margin-bottom: 1.5rem; }
        .timeline-item .tl-dot { position: absolute; left: -1.65rem; top: .3rem; width: 20px; height: 20px; border-radius: 50%; border: 3px solid #f5f6f8; display: flex; align-items: center; justify-content: center; font-size: .7rem; }
        .timeline-item .tl-content { background: var(--c5); border: 1px solid #e5e7eb; border-radius: 8px; padding: .75rem 1rem; }

        /* ── Pagination ── */
        .pagination .page-link { background: var(--c5); border-color: #d1d5db; color: #6b7280; }
        .pagination .page-link:hover { background: var(--c4-light); color: var(--c4); border-color: var(--c4); }
        .pagination .active .page-link { background: var(--c1); border-color: var(--c1); color: var(--c5); }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.show { transform: translateX(0); }
            #topbar, #main-content { left: 0; margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- Sidebar -->
<nav id="sidebar">
    <div class="brand">
        <i class="bi bi-people-fill me-2" style="color:var(--accent)"></i>
        Sistema RRHH
    </div>
    <div class="py-2">
        <div class="nav-label">General</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>

        <div class="nav-label mt-1">Reclutamiento</div>
        <a href="{{ route('candidatos.index') }}" class="nav-link {{ request()->routeIs('candidatos.*') ? 'active' : '' }}">
            <i class="bi bi-person-plus"></i> Candidatos
        </a>
        <a href="{{ route('vacantes.index') }}" class="nav-link {{ request()->routeIs('vacantes.*') ? 'active' : '' }}">
            <i class="bi bi-briefcase"></i> Vacantes
        </a>
        <a href="{{ route('entrevistas.index') }}" class="nav-link {{ request()->routeIs('entrevistas.*') ? 'active' : '' }}">
            <i class="bi bi-chat-dots"></i> Entrevistas
        </a>

        <div class="nav-label mt-1">Empleados</div>
        <a href="{{ route('empleados.index') }}" class="nav-link {{ request()->routeIs('empleados.*') && !request()->routeIs('empleados.bitacora') ? 'active' : '' }}">
            <i class="bi bi-person-badge"></i> Empleados
        </a>
        <a href="{{ route('asistencias.index') }}" class="nav-link {{ request()->routeIs('asistencias.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i> Asistencias
        </a>
        <a href="{{ route('amonestaciones.index') }}" class="nav-link {{ request()->routeIs('amonestaciones.*') ? 'active' : '' }}">
            <i class="bi bi-exclamation-triangle"></i> Amonestaciones
        </a>
        <a href="{{ route('evaluaciones.index') }}" class="nav-link {{ request()->routeIs('evaluaciones.*') ? 'active' : '' }}">
            <i class="bi bi-clipboard-check"></i> Evaluaciones
        </a>
        <a href="{{ route('salidas.index') }}" class="nav-link {{ request()->routeIs('salidas.*') ? 'active' : '' }}">
            <i class="bi bi-door-open"></i> Salidas
        </a>

        <div class="nav-label mt-1">Administración</div>
        <a href="{{ route('departamentos.index') }}" class="nav-link {{ request()->routeIs('departamentos.*') ? 'active' : '' }}">
            <i class="bi bi-diagram-3"></i> Departamentos
        </a>
        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="bi bi-person-gear"></i> Usuarios
        </a>
    </div>
</nav>

<!-- Topbar -->
<header id="topbar">
    <button class="btn btn-sm btn-outline-secondary d-md-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
        <i class="bi bi-list"></i>
    </button>
    <span class="fw-semibold text-white ms-1">@yield('title', 'Dashboard')</span>
    <div class="ms-auto d-flex align-items-center gap-3">
        <span class="text-muted small"><i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger">
                <i class="bi bi-box-arrow-right"></i>
            </button>
        </form>
    </div>
</header>

<!-- Main Content -->
<main id="main-content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Revisa los errores:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
@stack('scripts')
</body>
</html>
