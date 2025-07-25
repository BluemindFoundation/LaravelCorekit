<?php

namespace Corekit\Support;

use Illuminate\Support\Facades\App;
use Corekit\Contracts\TranslationServiceInterface;

class TranslationBulkLoader
{
    public static function load(array $entities, ?string $locale = null): void
    {
        if (empty($entities)) return;

        $locale = $locale ?? App::getLocale();

        $first = $entities[0];
        if (!method_exists($first, 'getTable') || !property_exists($first, 'translatable')) {
            throw new \InvalidArgumentException('Entities must use HasTranslations and define $translatable.');
        }

        $table = $first->getTable();
        $ids = array_map(fn($e) => $e->id, $entities);

        /** @var TranslationServiceInterface $translationService */
        $translationService = App::make(TranslationServiceInterface::class);

        $translations = $translationService->loadTranslations($table, $ids, $locale);

        foreach ($entities as $entity) {
            $entity->setTranslations($translations[$entity->id] ?? []);
        }
    }
}