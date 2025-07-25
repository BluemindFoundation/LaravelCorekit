<?php

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetAppLocaleFromHeader
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->header('Accept-Language', 'en');

        // Extract locale from header (e.g., 'fr-FR' -> 'fr')
        $locale = substr($locale, 0, 2);

        // Validate locale
        if (in_array($locale, ['en', 'fr'])) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}