<?php

namespace App\Policies;

use App\Models\Usuario;
use App\Models\Venta;

class VentaPolicy
{
    public function viewAny(Usuario $user): bool
    {
        return $user->esAdministrador() || $user->esGerente() || $user->esCliente();
    }

    public function view(Usuario $user, Venta $venta): bool
    {
        if ($user->esAdministrador() || $user->esGerente()) {
            return true;
        }

        return $user->esCliente() && $venta->cliente_id === $user->id;
    }

    public function create(Usuario $user): bool
    {
        return $user->esCliente() || $user->esAdministrador();
    }

    public function update(Usuario $user, Venta $venta): bool
    {
        return $user->esAdministrador() || $user->esGerente();
    }

    public function delete(Usuario $user, Venta $venta): bool
    {
        return $user->esAdministrador();
    }
}
