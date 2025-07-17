<?php

namespace App\Policies;

use App\Models\CotizacionPartida;
use App\Models\Usuario;
use Illuminate\Auth\Access\Response;

class CotizacionPartidaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Usuario $usuario): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Usuario $usuario, CotizacionPartida $cotizacionPartida): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Usuario $usuario): bool
    {
        return false;
    }

    /**
     * Puede editar la partida si:
     * - La cotización NO ha sido emitida como pedido
     * - Tiene permiso adecuado
     */
    public function update(Usuario $usuario, CotizacionPartida $partida): bool
    {
        return !$partida->cotizacion->pedido
            && $usuario->can('Editar Partida');
    }

        /**
     * Puede eliminar la partida si:
     * - La cotización NO ha sido emitida como pedido
     * - Tiene permiso adecuado
     */
    public function delete(Usuario $usuario, CotizacionPartida $partida): bool
    {
        return !$partida->cotizacion->pedido
            && $usuario->can('Eliminar Partida');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Usuario $usuario, CotizacionPartida $cotizacionPartida): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Usuario $usuario, CotizacionPartida $cotizacionPartida): bool
    {
        return false;
    }
}
