<?php

namespace App\Policies;

use App\Models\Usuario;
use App\Models\Venta;
use Illuminate\Auth\Access\Response;

class VentaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Usuario $user): bool
    {
        return in_array($user->rol, ['administrador', 'gerente']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Usuario $user, Venta $venta): bool
    {
        if (in_array($user->rol, ['administrador', 'gerente'])) {
            return true;
        }
        return $user->rol === 'cliente' && $venta->cliente_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Usuario $user): bool
    {
        return $user->rol === 'cliente';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Usuario $user, Venta $venta): bool
    {
        return $user->rol === 'administrador';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Usuario $user, Venta $venta): bool
    {
        return $user->rol === 'administrador';
    }

    /**
     * Determine whether the user can cancel the sale.
     */
    public function cancel(Usuario $user, Venta $venta): bool
    {
        return $user->rol === 'administrador' || ($user->rol === 'cliente' && $venta->usuario_id === $user->id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Venta $venta): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Venta $venta): bool
    {
        return false;
    }
}
