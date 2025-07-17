<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Cotizacion;
use App\Policies\CotizacionPolicy;
use App\Models\CotizacionPartida;
use App\Policies\CotizacionPartidaPolicy;


class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Cotizacion::class         => CotizacionPolicy::class,
        CotizacionPartida::class  => CotizacionPartidaPolicy::class,
    ];


    public function boot(): void
    {
        // No necesitas llamar a registerPolicies(), Laravel lo hace solo
    }
}
