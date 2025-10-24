<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SubscriptionConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'label',
        'description',
    ];

    /**
     * Get a config value by key
     */
    public static function get(string $key, $default = null)
    {
        return Cache::remember("subscription_config_{$key}", 3600, function () use ($key, $default) {
            $config = static::where('key', $key)->first();
            
            if (!$config) {
                return $default;
            }

            return static::castValue($config->value, $config->type);
        });
    }

    /**
     * Set a config value
     */
    public static function set(string $key, $value): bool
    {
        $config = static::where('key', $key)->first();
        
        if (!$config) {
            return false;
        }

        $config->update(['value' => (string) $value]);
        Cache::forget("subscription_config_{$key}");
        
        return true;
    }

    /**
     * Cast value to proper type
     */
    protected static function castValue($value, string $type)
    {
        return match($type) {
            'integer' => (int) $value,
            'decimal' => (float) $value,
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            default => $value,
        };
    }

    /**
     * Get all configs as key-value pairs
     */
    public static function getAllConfigs(): array
    {
        return static::query()->get()->mapWithKeys(function ($config) {
            return [$config->key => static::castValue($config->value, $config->type)];
        })->toArray();
    }
}
