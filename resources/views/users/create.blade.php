@extends('layouts.app')
@section('title', 'Nuevo Usuario')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0 text-white">Nuevo Usuario</h5>
</div>
<div class="row"><div class="col-md-5">
<form action="{{ route('users.store') }}" method="POST">
@csrf
<div class="card">
    <div class="card-body">
        <div class="mb-3"><label class="form-label small fw-semibold">Nombre *</label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
        <div class="mb-3"><label class="form-label small fw-semibold">Correo *</label><input type="email" name="email" class="form-control" value="{{ old('email') }}" required></div>
        <div class="mb-3"><label class="form-label small fw-semibold">Contraseña *</label><input type="password" name="password" class="form-control" required minlength="8"></div>
        <div class="mb-3"><label class="form-label small fw-semibold">Confirmar Contraseña *</label><input type="password" name="password_confirmation" class="form-control" required></div>
        <div class="mb-3">
            <label class="form-label small fw-semibold">Rol *</label>
            <select name="role" class="form-select" required>
                <option value="">— Seleccionar —</option>
                @foreach($roles as $r)
                <option value="{{ $r->name }}">{{ $r->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Crear</button>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary ms-2">Cancelar</a>
    </div>
</div>
</form>
</div></div>
@endsection
