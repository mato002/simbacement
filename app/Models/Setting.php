<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
    ];

    public static function getValue(string $key, mixed $default = null, string $group = 'general'): mixed
    {
        $cacheKey = "settings.{$group}.{$key}";

        return Cache::rememberForever($cacheKey, function () use ($key, $group, $default) {
            $setting = static::query()
                ->where('group', $group)
                ->where('key', $key)
                ->first();

            if (! $setting) {
                return $default;
            }

            return match ($setting->type) {
                'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
                'integer' => (int) $setting->value,
                'json' => json_decode($setting->value ?? 'null', true),
                default => $setting->value ?? $default,
            };
        });
    }

    public static function setValue(string $key, mixed $value, string $group = 'general', string $type = 'string'): void
    {
        $stored = match ($type) {
            'boolean' => $value ? '1' : '0',
            'json' => json_encode($value),
            default => $value === null ? null : (string) $value,
        };

        static::query()->updateOrCreate(
            ['group' => $group, 'key' => $key],
            ['value' => $stored, 'type' => $type],
        );

        Cache::forget("settings.{$group}.{$key}");
        Cache::forget("settings.group.{$group}");
    }

    /**
     * @return array<string, mixed>
     */
    public static function group(string $group): array
    {
        return Cache::rememberForever("settings.group.{$group}", function () use ($group) {
            return static::query()
                ->where('group', $group)
                ->get()
                ->mapWithKeys(function (self $setting) {
                    $value = match ($setting->type) {
                        'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
                        'integer' => (int) $setting->value,
                        'json' => json_decode($setting->value ?? 'null', true),
                        default => $setting->value,
                    };

                    return [$setting->key => $value];
                })
                ->all();
        });
    }

    public static function whatsappChatUrl(): ?string
    {
        if (! static::getValue('enabled', false, 'whatsapp')) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', (string) static::getValue('phone', '', 'whatsapp')) ?: '';

        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            $digits = '254'.substr($digits, 1);
        }

        $url = "https://wa.me/{$digits}";
        $message = trim((string) static::getValue('message', '', 'whatsapp'));

        if ($message !== '') {
            $url .= '?text='.rawurlencode($message);
        }

        return $url;
    }
}
