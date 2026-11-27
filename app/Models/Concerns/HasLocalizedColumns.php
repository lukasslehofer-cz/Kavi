<?php

namespace App\Models\Concerns;

use App\Helpers\Site;

/**
 * Překlady obsahu v DB jako sloupce s příponou jazyka (name_en, name_sk).
 *
 * Čeština je ve výchozím sloupci (name, případně title_cs). Když překlad do jazyka
 * chybí, zobrazí se čeština – pro slovenštinu přirozenější než angličtina.
 */
trait HasLocalizedColumns
{
    /**
     * Hodnota pole v jazyce (výchozí = jazyk requestu), prázdný překlad = český sloupec.
     *
     * @param  string  $field  základ názvu sloupce (name, title)
     * @param  string|null  $baseColumn  český sloupec, když se neshoduje s $field (title_cs)
     */
    public function localizedValue(string $field, ?string $locale = null, ?string $baseColumn = null): mixed
    {
        $locale ??= app()->getLocale();

        if ($locale !== Site::PRIMARY_LOCALE) {
            $value = $this->getAttribute($field . '_' . $locale);
            if (! empty($value)) {
                return $value;
            }
        }

        return $this->getAttribute($baseColumn ?? $field);
    }
}
