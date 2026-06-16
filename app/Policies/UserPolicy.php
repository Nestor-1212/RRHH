<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Super Admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Super Admin');
    }

    public function update(User $user, User $target): bool
    {
        // Super Admin puede editar cualquier usuario excepto a sí mismo desde aquí
        return $user->hasRole('Super Admin') && $user->id !== $target->id;
    }

    public function delete(User $user, User $target): bool
    {
        return $user->hasRole('Super Admin') && $user->id !== $target->id;
    }
}
