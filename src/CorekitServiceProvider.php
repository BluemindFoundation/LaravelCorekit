<?php

namespace Corekit;

use Illuminate\Support\ServiceProvider;

class CorekitServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        \Corekit\Macros\ResponseMacros::register();
    }

    public function register()
    {
        // Register bindings, if any
    }
}