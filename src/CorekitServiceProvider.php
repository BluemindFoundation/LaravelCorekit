<?php

namespace Corekit;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class CorekitServiceProvider extends ServiceProvider
{
    public function boot()
    {

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'corekit');

        $this->publishes([
            __DIR__ . '/../resources/lang' => App::resourcePath('lang/vendor/corekit'),
        ], 'corekit-translations');
        \Corekit\Macros\ResponseMacros::register();
    }

    public function register()
    {
        // Register bindings, if any
    }
}