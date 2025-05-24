<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('layouts.partials.topbar', function ($view) {
            $view->with('usuario', auth()->user());
        });
    }
}
