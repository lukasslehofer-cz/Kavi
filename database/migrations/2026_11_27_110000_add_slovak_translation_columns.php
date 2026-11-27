<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Slovenské překlady obsahu (kavibox.sk) – stejný vzor jako sloupce _en.
 *
 * Prázdný slovenský sloupec = zobrazí se čeština (HasLocalizedColumns).
 * Sloupce se přidávají na konec tabulky bez after(), aby MySQL 8 mohla použít
 * ALGORITHM=INSTANT: products a roasteries nočně čte KAVI app krátkými dotazy.
 * Nové sloupce se KAVI app negrantují (čte jen české základní sloupce).
 */
return new class extends Migration
{
    private const COLUMNS = [
        'products' => [
            'name_sk' => 'string',
            'description_sk' => 'text',
            'short_description_sk' => 'text',
        ],
        'roasteries' => [
            'name_sk' => 'string',
            'short_description_sk' => 'text',
            'full_description_sk' => 'text',
            'country_sk' => 'string',
            'city_sk' => 'string',
        ],
        'posts' => [
            'title_sk' => 'string',
            'slug_sk' => 'string',
            'perex_sk' => 'text',
            'content_sk' => 'longText',
        ],
        'tags' => [
            'name_sk' => 'string',
            'slug_sk' => 'string',
        ],
        'announcement_banners' => [
            'message_sk' => 'text',
            'title_sk' => 'string',
            'link_label_sk' => 'string:80',
            'link_url_sk' => 'string',
        ],
    ];

    public function up(): void
    {
        foreach (self::COLUMNS as $table => $columns) {
            $missing = array_filter($columns, fn ($column) => ! Schema::hasColumn($table, $column), ARRAY_FILTER_USE_KEY);
            if ($missing === []) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint) use ($missing) {
                foreach ($missing as $column => $type) {
                    [$method, $length] = array_pad(explode(':', $type), 2, null);
                    $definition = $length ? $blueprint->{$method}($column, (int) $length) : $blueprint->{$method}($column);
                    $definition->nullable();
                }
            });
        }

        // Slugy článků a štítků jsou unikátní stejně jako slug_en
        foreach (['posts', 'tags'] as $table) {
            Schema::table($table, function (Blueprint $blueprint) use ($table) {
                $blueprint->unique('slug_sk', "{$table}_slug_sk_unique");
            });
        }
    }

    public function down(): void
    {
        foreach (['posts', 'tags'] as $table) {
            Schema::table($table, function (Blueprint $blueprint) use ($table) {
                $blueprint->dropUnique("{$table}_slug_sk_unique");
            });
        }

        foreach (self::COLUMNS as $table => $columns) {
            $existing = array_values(array_filter(array_keys($columns), fn ($column) => Schema::hasColumn($table, $column)));
            if ($existing !== []) {
                Schema::table($table, fn (Blueprint $blueprint) => $blueprint->dropColumn($existing));
            }
        }
    }
};
