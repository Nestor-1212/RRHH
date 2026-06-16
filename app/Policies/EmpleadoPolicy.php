<?php

namespace App\Policies;

use App\Models\Empleado;
use App\Models\User;

class EmpleadoPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // todos los roles autenticados pueden listar
    }

    public function view(User $user, Empleado $empleado): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Recursos Humanos']);
    }

    public function update(User $user, Empleado $empleado): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Recursos Humanos']);
    }

    public function delete(User $user, Empleado $empleado): bool
    {
        return $user->hasRole('Super Admin');
    }

    public function manageDocuments(User $user, Empleado $empleado): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Recursos Humanos']);
    }
}
