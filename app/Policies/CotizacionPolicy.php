<?php

namespace App\Policies;

use App\Models\Cotizacion;
use App\Models\Usuario;
use Illuminate\Auth\Access\Response;

class CotizacionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Usuario $usuario): bool
    {
        return false;
    }

        /**
     * Ver cotización (siempre que tenga permiso de acceso general)
     */
    public function view(Usuario $usuario, Cotizacion $cotizacion): bool
    {
        return $usuario->can('Monitor de Cotizaciones');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Usuario $usuario): bool
    {
        return false;
    }

    /**
     * Editar cotización:
     * - Si aún no es pedido y tiene permiso "editar cotizacion"
     * - O si ya es pedido y tiene permiso "editar pedido"
     */
    public function update(Usuario $usuario, Cotizacion $cotizacion): bool
    {
        if ($cotizacion->pedido) {
            return $usuario->can('Editar Pedido');
        }
        return $usuario->can('Editar Cotizacion');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Usuario $usuario, Cotizacion $cotizacion): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Usuario $usuario, Cotizacion $cotizacion): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Usuario $usuario, Cotizacion $cotizacion): bool
    {
        return false;
    }

        /**
     * Emitir pedido:
     * - Solo si tiene permiso
     * - Ya hay orden de compra cargada
     * - Aún no ha sido emitida como pedido
     */
    public function emitir(Usuario $usuario, Cotizacion $cotizacion): bool
    {
        return $usuario->can('Emitir Pedido')
            && $cotizacion->orden_de_venta
            && !$cotizacion->pedido;
    }
}
