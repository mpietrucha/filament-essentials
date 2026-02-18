<?php

namespace Mpietrucha\Filament\Essentials;

use Illuminate\Support\Facades\Blade;
use Mpietrucha\Utility\Arr;
use Mpietrucha\Utility\Str;

/**
 * @phpstan-import-type MixedArray from \Mpietrucha\Utility\Arr
 */
abstract class Component
{
    /**
     * @param  MixedArray  $attributes
     */
    public static function renderBadge(string $badge, array $attributes): string
    {
        $attributes = Arr::prepend($attributes, $badge, 'badge');

        $component = '<x-filament::badge {{ $attributes }}>{{ $badge }}</x-filament::badge>';

        return Blade::render($component, $attributes);
    }

    public static function renderTextColumnBadge(string $badge): string
    {
        $attributes = [
            'size' => 'sm',
            'class' => 'ml-2',
        ];

        $badge = Str::sprintf('+%s', $badge);

        return static::renderBadge($badge, $attributes);
    }

    public static function renderSelectOptionWithAvatar(string $option, string $avatar): string
    {
        $attributes = [
            'option' => $option,
            'avatar' => $avatar,
        ];

        $component = '<x-essentials::select-title-with-avatar :avatar="$avatar">{{ $option }}</x-essentials::select-title-with-avatar>';

        return Blade::render($component, $attributes);
    }
}
