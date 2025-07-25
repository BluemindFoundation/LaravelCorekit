<?php

namespace Corekit;

use Corekit\Services\HttpClient;
use Corekit\Macros\ResponseMacros;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Corekit\Services\TranslationService;
use Corekit\Contracts\HttpClientInterface;
use Corekit\Contracts\ApiRenderableException;
use Corekit\Contracts\TranslationServiceInterface;

class CorekitServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Register custom response macros
        ResponseMacros::register();

        // Load migrations from the package
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Load translations
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'corekit');

        // Only publish when running in console (e.g., artisan commands)
        if ($this->app->runningInConsole()) {
            // Publish translations (optional)
            $this->publishes([
                __DIR__ . '/../resources/lang' => App::langPath('vendor/corekit'),
            ], 'corekit-translations');

            // Publish config file (optional)
            $this->publishes([
                __DIR__ . '/../config/microservice-auth.php' => App::configPath('microservice-auth.php'),
            ], 'corekit-microservice-auth-config');
        }

        // Merge config automatically, whether published or not
        $this->mergeConfigFrom(
            __DIR__ . '/../config/microservice-auth.php',
            'microservice-auth'
        );

        // Exception Handler
        $this->app->make('Illuminate\Contracts\Debug\ExceptionHandler')->renderable(function (ApiRenderableException $e) {
            return $e->toApiResponse();
        });
    }

    public function register(): void
    {
        $this->app->singleton(HttpClientInterface::class, function ($app) {
            return new HttpClient();
        });
        $this->app->bind(TranslationServiceInterface::class, TranslationService::class);
        $this->app->alias(HttpClientInterface::class, 'corekit.httpclient');
    }
}