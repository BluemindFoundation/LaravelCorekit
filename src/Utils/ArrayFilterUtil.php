<?php

namespace Corekit\Utils;

class ArrayFilterUtil
{
    /**
     * Filtre un tableau associatif pour ne conserver que les paires clé-valeur
     * où la valeur n'est pas vide ou une chaîne vide.
     *
     * @param array $array Le tableau à filtrer.
     * @return array Le tableau filtré.
     */
    public static function removeEmptyValues(array $array): array
    {
        return array_filter($array, static function ($value) {
            if ($value === null) return false;
            if (is_array($value)) return count($value) > 0;
            if (is_string($value)) return trim($value) !== '';
            return true;
        });
    }
}