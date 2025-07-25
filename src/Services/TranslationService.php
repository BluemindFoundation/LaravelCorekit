<?php

namespace Corekit\Services;

use Corekit\Models\Translation;
use Corekit\Contracts\TranslationServiceInterface;



class TranslationService implements TranslationServiceInterface
{
    public function saveTranslations(string $tableName, string $rowId, string $columnName, array $translations): void
    {
        foreach ($translations as $locale => $value) {
            Translation::updateOrCreate([
                'table_name' => $tableName,
                'row_id' => $rowId,
                'column_name' => $columnName,
                'locale' => $locale,
            ], [
                'value' => $value,
            ]);
        }
    }

    public function getTranslation(string $tableName, string $rowId, string $columnName, string $locale): ?string
    {
        return Translation::where('table_name', $tableName)
            ->where('row_id', $rowId)
            ->where('column_name', $columnName)
            ->where('locale', $locale)
            ->value('value');
    }
    public function loadTranslations(string $tableName, array $rowIds, string $locale): array
    {
        return Translation::where('table_name', $tableName)
            ->whereIn('row_id', $rowIds)
            ->where('locale', $locale)
            ->get()
            ->groupBy('row_id')
            ->map(fn($group) => $group->keyBy('column_name')->map->value)
            ->toArray();
    }
}