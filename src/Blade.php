<?php

namespace Mpietrucha\Filament\Essentials;

use Illuminate\View\ComponentAttributeBag;

abstract class Blade extends \Illuminate\Support\Facades\Blade
{
    /**
     * @param  array<mixed>  $attributes
     */
    public static function renderBadge(string $badge, array $attributes): string
    {
        $attributes = [
            'badge' => $badge,
            'attributes' => new ComponentAttributeBag($attributes),
        ];

        $component = '<x-filament::badge {{ $attributes }}>{{ $badge }}</x-filament::badge>';

        return static::render($component, $attributes);
    }

    public static function renderPrefixBadge(string $badge, string $size = 'sm'): string
    {
        $attributes = [
            'size' => $size,
            'class' => 'mr-2',
        ];

        return static::renderBadge($badge, $attributes);
    }

    public static function renderSuffixBadge(string $badge, string $size = 'sm'): string
    {
        $attributes = [
            'size' => $size,
            'class' => 'ml-2',
        ];

        return static::renderBadge($badge, $attributes);
    }

    public static function renderSelectOptionWithAvatar(string $option, string $avatar): string
    {
        $attributes = [
            'option' => $option,
            'avatar' => $avatar,
        ];

        $component = '<x-filament-essentials::select-option-with-avatar :avatar="$avatar">{{ $option }}</x-filament-essentials::select-option-with-avatar>';

        return static::render($component, $attributes);
    }
}
