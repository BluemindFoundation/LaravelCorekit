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
        return array_filter($array, function ($value) {
            return !is_null($value) && $value !== '';
        });
    }
}