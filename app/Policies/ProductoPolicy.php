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
        return $user->puedeGestionarProductos();
    }

    public function update(Usuario $user, Producto $producto): bool
    {
        return $user->esAdministrador() || $user->esGerente() || ($user->esVendedor() && $producto->usuario_id === $user->id);
    }

    public function delete(Usuario $user, Producto $producto): bool
    {
        return $user->esAdministrador() || $user->esGerente() || ($user->esVendedor() && $producto->usuario_id === $user->id);
    }

    public function buy(Usuario $user): bool
    {
        return $user->esCliente();
    }

    public function comprar(Usuario $user): bool
    {
        return $this->buy($user);
    }
}
