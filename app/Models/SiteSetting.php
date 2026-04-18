<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = [
        'setting_key',
        'setting_value',
        'group_name',
    ];

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $value = Cache::remember(
            "site_setting:{$key}",
            3600,
            fn () => static::where('setting_key', $key)->value('setting_value')
        );

        return $value ?? $default;
    }

    protected static function booted(): void
    {
        static::saved(fn (self $m) => Cache::forget("site_setting:{$m->setting_key}"));
        static::deleted(fn (self $m) => Cache::forget("site_setting:{$m->setting_key}"));
    }
}
