<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group', 'label', 'description'];

    // ── Get a setting value by key ────────────────────────────────────────────
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = Cache::remember("setting:{$key}", 3600, fn () =>
            static::where('key', $key)->first()
        );

        if (!$setting) return $default;

        return match ($setting->type) {
            'boolean' => (bool) $setting->value,
            'integer' => (int)  $setting->value,
            'json'    => json_decode($setting->value, true),
            default   => $setting->value,
        };
    }

    // ── Set a setting value ───────────────────────────────────────────────────
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => is_array($value) ? json_encode($value) : $value]
        );
        Cache::forget("setting:{$key}");
    }

    // ── Get all settings in a group as key→value map ─────────────────────────
    public static function group(string $group): array
    {
        return static::where('group', $group)
            ->get()
            ->mapWithKeys(fn ($s) => [$s->key => $s->value])
            ->toArray();
    }
}
