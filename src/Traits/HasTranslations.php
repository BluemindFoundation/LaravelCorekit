<?php

namespace Corekit\Traits;

use Corekit\Models\Translation;
use Illuminate\Support\Facades\App;

trait HasTranslations
{
    protected array $translatable = [];

    public function getTranslation(string $column, ?string $locale = null)
    {
        $locale = $locale ?? App::getLocale();

        $translation = Translation::where('table_name', $this->getTable())
            ->where('column_name', $column)
            ->where('row_id', $this->id)
            ->where('locale', $locale)
            ->first();

        if ($translation) {
            return $translation->value;
        }

        // fallback : retourne la valeur originale (non traduite) si elle existe dans la table source
        return $this->getOriginal($column);
    }

    // Enregistrer ou mettre à jour une traduction
    public function setTranslation(string $column, string $locale, string $value)
    {
        return Translation::updateOrCreate(
            [
                'table_name' => $this->getTable(),
                'column_name' => $column,
                'row_id' => $this->id,
                'locale' => $locale,
            ],
            ['value' => $value]
        );
    }

    // Override pour retourner la traduction automatiquement
    public function getAttribute($key)
    {
        if (in_array($key, $this->translatable)) {
            return $this->getTranslation($key);
        }

        return parent::getAttribute($key);
    }
}