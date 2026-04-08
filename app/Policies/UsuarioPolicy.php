<?php

namespace App\Policies;

use App\Models\Usuario;

class UsuarioPolicy
{
    public function viewAny(Usuario $user): bool
    {
        return $user->esAdministrador() || $user->esGerente();
    }

    public function view(Usuario $user, Usuario $model): bool
    {
        return $user->esAdministrador() || $user->id === $model->id || ($user->esGerente() && $model->rol === 'cliente');
    }

    public function create(Usuario $user): bool
    {
        return $user->esAdministrador();
    }

    public function update(Usuario $user, Usuario $model): bool
    {
        if ($user->esAdministrador()) {
            return true;
        }

        if ($user->esGerente()) {
            return $model->rol === 'cliente';
        }

        return $user->id === $model->id;
    }

    public function delete(Usuario $user, Usuario $model): bool
    {
        if ($user->esAdministrador()) {
            return $user->id !== $model->id;
        }

        return false;
    }
}
