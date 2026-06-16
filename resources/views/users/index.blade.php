@extends('layouts.app')
@section('title', 'Usuarios del Sistema')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0 text-white">Usuarios del Sistema</h5>
    <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Nuevo Usuario</a>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Usuario</th><th>Correo</th><th>Rol</th><th>Creado</th><th class="text-end">Acciones</th></tr></thead>
            <tbody>
                @forelse($users as $u)
                <tr>
                    <td class="fw-semibold text-white">{{ $u->name }}</td>
                    <td class="text-muted">{{ $u->email }}</td>
                    <td>
                        @foreach($u->roles as $rol)
                        <span class="badge bg-primary">{{ $rol->name }}</span>
                        @endforeach
                    </td>
                    <td class="text-muted small">{{ $u->created_at->format('d/m/Y') }}</td>
                    <td class="text-end">
                        <a href="{{ route('users.edit', $u) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        @if($u->id !== auth()->id())
                        <form action="{{ route('users.destroy', $u) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('¿Eliminar usuario?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4 text-muted">Sin usuarios.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
