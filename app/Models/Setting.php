<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'label',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        if (!$setting) return $default;

        return match ($setting->type) {
            'boolean', 'bool' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            'integer', 'int' => (int) $setting->value,
            'float', 'decimal' => (float) $setting->value,
            'json', 'array' => json_decode($setting->value, true) ?? $default,
            default => $setting->value,
        };
    }

    public static function set(string $key, $value, string $group = 'general', string $type = 'string', ?string $label = null): self
    {
        $serialized = is_array($value) ? json_encode($value) : (string)$value;
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $serialized,
                'group' => $group,
                'type' => $type,
                'label' => $label ?? ucwords(str_replace('_', ' ', $key)),
            ]
        );
    }
}