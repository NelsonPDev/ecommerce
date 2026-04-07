<?php

namespace App\Policies;

use App\Models\Producto;
use App\Models\Usuario;

class ProductoPolicy
{
    public function viewAny(?Usuario $user = null): bool
    {
        return true;
    }

    public function view(?Usuario $user = null, ?Producto $producto = null): bool
    {
        return true;
    }

    public function create(Usuario $user): bool
    {
        return $user->esAdministrador() || $user->esGerente();
    }

    public function update(Usuario $user, Producto $producto): bool
    {
        return $user->esAdministrador() || $user->esGerente();
    }

    public function delete(Usuario $user, Producto $producto): bool
    {
        return $user->esAdministrador() || $user->esGerente();
    }

    public function buy(Usuario $user): bool
    {
        return $user->esCliente();
    }
}
