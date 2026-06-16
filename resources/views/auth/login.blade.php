<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — Sistema RRHH</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: linear-gradient(145deg, #e28000 0%, #ff9800 55%, #008080 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #FFFFFF;
            border: none;
            border-radius: 16px;
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,.3);
        }
        .login-icon {
            width: 64px; height: 64px;
            background: linear-gradient(135deg, #e28000, #ffc340);
            border-radius: 16px; display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem; color: #fff; margin: 0 auto 1.5rem;
        }
        .form-control {
            background: #fff; border-color: #d1d5db; color: #1f2937;
            padding: .65rem 1rem;
        }
        .form-control:focus { background: #fff; border-color: #008080; color: #1f2937; box-shadow: 0 0 0 .2rem rgba(0,128,128,.2); }
        .form-control::placeholder { color: #9ca3af; }
        .btn-login {
            background: #008080; border: none; color: #fff;
            font-weight: 600; padding: .65rem; width: 100%;
            border-radius: 8px; transition: background .2s;
            font-size: .95rem;
        }
        .btn-login:hover { background: #006666; color: #fff; }
        .form-label { color: #374151; font-size: .78rem; font-weight: 600; letter-spacing: .5px; text-transform: uppercase; }
        .divider { height: 4px; background: linear-gradient(90deg, #e28000, #ffc340, #008080); border-radius: 2px; margin-bottom: 1.5rem; }
    </style>
</head>
<body>
<div class="login-card">
    <div class="login-icon">
        <i class="bi bi-people-fill"></i>
    </div>
    <h4 class="text-center fw-bold mb-1" style="color:#1f2937">Sistema RRHH</h4>
    <p class="text-center text-muted small mb-2">Recursos Humanos — Iniciar sesión</p>
    <div class="divider"></div>

    @if($errors->any())
        <div class="alert alert-danger py-2 small mb-3">
            <i class="bi bi-exclamation-circle me-1"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label text-muted small fw-semibold">CORREO ELECTRÓNICO</label>
            <input type="email" name="email" class="form-control" placeholder="admin@rrhh.com"
                   value="{{ old('email') }}" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label text-muted small fw-semibold">CONTRASEÑA</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        <div class="mb-4 d-flex align-items-center">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label text-muted small" for="remember">Recordarme</label>
            </div>
        </div>
        <button type="submit" class="btn-login">
            <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar
        </button>
    </form>
    <p class="text-center text-muted small mt-4 mb-0">
        &copy; {{ date('Y') }} Sistema Integral de RRHH
    </p>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
