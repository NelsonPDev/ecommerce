<?php

namespace App\Policies;

use App\Models\Usuario;
use Illuminate\Auth\Access\Response;

class UsuarioPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Usuario $user): bool
    {
        return $user->rol === 'administrador';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Usuario $user, Usuario $model): bool
    {
        return $user->rol === 'administrador' || $user->id === $model->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Usuario $user): bool
    {
        // Solo administrador puede crear usuarios
        return $user->rol === 'administrador';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Usuario $user, Usuario $model): bool
    {
        // Admin puede editar a todos
        if ($user->rol === 'administrador') {
            return true;
        }

        // Gerente puede editar solo clientes
        if ($user->rol === 'gerente') {
            return $model->rol === 'cliente';
        }

        // Cliente solo puede editar su propio perfil
        return $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Usuario $user, Usuario $model): bool
    {
        // Solo administrador puede eliminar
        return $user->rol === 'administrador';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
