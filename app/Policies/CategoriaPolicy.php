<?php

namespace App\Policies;

use App\Models\Categoria;
use App\Models\Usuario;

class CategoriaPolicy
{
    public function viewAny(?Usuario $user = null): bool
    {
        return true;
    }

    public function view(?Usuario $user = null, ?Categoria $categoria = null): bool
    {
        return true;
    }

    public function create(Usuario $user): bool
    {
        return $user->esAdministrador() || $user->esGerente();
    }

    public function update(Usuario $user, Categoria $categoria): bool
    {
        return $user->esAdministrador() || $user->esGerente();
    }

    public function delete(Usuario $user, Categoria $categoria): bool
    {
        return $user->esAdministrador() || $user->esGerente();
    }
}
