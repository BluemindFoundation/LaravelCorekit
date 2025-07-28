<?php

namespace Corekit;

use Corekit\Services\HttpClient;
use Corekit\Macros\ResponseMacros;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

use Illuminate\Support\ServiceProvider;
use Corekit\Services\TranslationService;
use Illuminate\Support\Facades\Response;
use Corekit\Contracts\HttpClientInterface;
use Corekit\Contracts\ApiRenderableException;

use Corekit\Contracts\TranslationServiceInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class CorekitServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerBindings();
    }

    public function boot(): void
    {
        $this->registerResponseMacros();
        $this->loadTranslations();
        $this->loadMigrations();
        $this->publishResources();
        $this->mergeConfiguration();
        $this->registerExceptionHandling();
    }

    protected function registerBindings(): void
    {
        $this->app->singleton(HttpClientInterface::class, fn() => new HttpClient());
        $this->app->bind(TranslationServiceInterface::class, TranslationService::class);
        $this->app->alias(HttpClientInterface::class, 'corekit.httpclient');
    }

    protected function registerResponseMacros(): void
    {
        ResponseMacros::register();
    }

    protected function loadTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'corekit');
    }

    protected function loadMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    protected function publishResources(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/lang' => App::langPath('vendor/corekit'),
            ], 'corekit-translations');

            $this->publishes([
                __DIR__ . '/../config/Gateway-auth.php' => App::configPath('Gateway-auth.php'),
            ], 'corekit-Gateway-auth-config');
        }
    }

    protected function mergeConfiguration(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/Gateway-auth.php',
            'Gateway-auth'
        );
    }

    protected function registerExceptionHandling(): void
    {
        // 🧠 Use Config::get() instead of the helper because helpers aren't always available in packages
        $enabled = Config::get('microservice-auth.api_exception_handling.enabled', true);
        $isDebug = Config::get('app.debug', false);

        // Disable in debug mode or via config
        if (!$enabled || $isDebug) {
            return;
        }


        $this->app->make('Illuminate\Contracts\Debug\ExceptionHandler')->renderable(function (ApiRenderableException $e) {
            return $e->toApiResponse();
        });

        $this->app->make('Illuminate\Contracts\Debug\ExceptionHandler')->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return Response::json([
                    'success' => false,
                    'message' => 'Route not found.',
                    'status_code' => 404,
                ], 404);
            }
        });

        $this->app->make('Illuminate\Contracts\Debug\ExceptionHandler')->renderable(function (MethodNotAllowedHttpException $e, $request) {
            if ($request->expectsJson()) {
                return Response::json([
                    'success' => false,
                    'message' => 'Method not allowed.',
                    'status_code' => 405,
                ], 405);
            }
        });
    }
}
