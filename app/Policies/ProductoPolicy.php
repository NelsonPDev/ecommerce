<?php

namespace App\Policies;

use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Auth\Access\Response;

class ProductoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Usuario $user): bool
    {
        // Todos pueden ver productos
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Usuario $user, Producto $producto): bool
    {
        // Todos pueden ver productos
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Usuario $user): bool
    {
        // Solo administrador puede crear productos
        return $user->rol === 'administrador';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Usuario $user, Producto $producto): bool
    {
        // Solo administrador puede editar productos
        return $user->rol === 'administrador';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Usuario $user, Producto $producto): bool
    {
        // Solo administrador puede eliminar productos
        return $user->rol === 'administrador';
    }

    /**
     * Determine whether the user can buy a product.
     */
    public function comprar(Usuario $user): bool
    {
        // Solo clientes pueden comprar
        return $user->rol === 'cliente';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Usuario $user, Producto $producto): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Usuario $user, Producto $producto): bool
    {
        return false;
    }
}
