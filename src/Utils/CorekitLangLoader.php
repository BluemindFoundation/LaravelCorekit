<?php

namespace Corekit\Utils;

class CorekitLangLoader
{
    public static function load(string $path): array
    {
        $translations = [];

        foreach (glob($path . '/*.php') as $file) {
            // Ne pas inclure messages.php lui-même
            if (basename($file) === 'messages.php') {
                continue;
            }

            $translations = array_merge($translations, require $file);
        }

        return $translations;
    }
}