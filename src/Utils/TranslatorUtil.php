<?php

namespace Corekit\Utils;

use Illuminate\Support\Facades\Lang;

class TranslatorUtil
{
    public static function message(string $key, array $replace = [], string|null $locale = null): string
    {
        return Lang::get("corekit::messages.{$key}", $replace, $locale);
    }
}