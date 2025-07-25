<?php

namespace Corekit\Contracts;

interface TranslationServiceInterface
{
    public function saveTranslations(string $tableName, string $rowId, string $columnName, array $translations): void;
    public function getTranslation(string $tableName, string $rowId, string $columnName, string $locale): ?string;
    public function  loadTranslations(string $tableName, array $rowIds, string $locale): array;
}