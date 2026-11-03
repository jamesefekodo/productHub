<?php

namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;

class Translator
{
    public static function get(string $key, array $replace = []): string
    {
        $locale = App::getLocale();

        // Try current locale
        $value = Lang::get($key, $replace, $locale);

        // If Laravel couldn't resolve it, it returns the key
        if ($value !== $key) {
            return $value;
        }

        // Try English fallback
        $fallback = Lang::get($key, $replace, 'en');

        if ($fallback !== $key) {
            return $fallback;
        }

        // Final fallback
        return $key;
    }
}
